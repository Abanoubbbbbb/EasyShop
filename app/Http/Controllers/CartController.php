<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // 🛒 عرض الكارت
    public function index()
    {
        $cart = Cart::with('items.product')
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        return view('frontend.cart.index', compact('cart'));
    }

    // ➕ إضافة للكارت
    public function add($id, Request $request)
    {
        $product = Product::findOrFail($id);
        $quantity = $request->quantity ?? 1;

        DB::beginTransaction();

        try {
            $cart = Cart::firstOrCreate([
                'user_id' => Auth::id(),
                'status' => 'active'
            ]);

            $item = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $id)
                ->first();

            if ($item) {

                $newQuantity = $item->quantity + $quantity;

                if ($newQuantity > $product->quantity) {
                    return back()->with('error', 'Not enough stock available');
                }

                $item->update([
                    'quantity' => $newQuantity
                ]);
            } else {

                if ($quantity > $product->quantity) {
                    return back()->with('error', 'Not enough stock available');
                }

                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $id,
                    'quantity' => $quantity,
                    'price' => $product->sale_price, // 🔥 مهم جداً
                ]);
            }

            DB::commit();
            return back()->with('success', 'Added to cart successfully 🛒');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong');
        }
    }

    // 🔄 تحديث الكمية
    public function update(Request $request, $id)
    {
        $item = CartItem::findOrFail($id);
        $product = Product::findOrFail($item->product_id);

        if ($request->quantity > $product->quantity) {
            return back()->with('error', 'Not enough stock available');
        }

        $item->update([
            'quantity' => $request->quantity
        ]);

        return back()->with('success', 'Cart updated');
    }

    // ❌ حذف عنصر
    public function remove($id)
    {
        CartItem::findOrFail($id)->delete();

        return back()->with('success', 'Item removed');
    }

    public function checkout(Request $request)
    {
        // 1. التحقق من البيانات المدخلة أولاً قبل فتح الـ Transaction
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        return DB::transaction(function () use ($request) {
            // 2. جلب السلة مع القفل لضمان عدم تعديلها أثناء العملية
            $cart = Cart::where('user_id', Auth::id())
                ->where('status', 'active')
                ->with('items.product')
                ->lockForUpdate()
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                throw new \Exception('السلة فارغة حالياً');
            }

            // 3. إنشاء الطلب (Order)
            $order = Order::create([
                'user_id' => Auth::id(),
                'company_id' => Auth::user()->company_id, // تأكد أن اليوزر مرتبط بشركة
                'customer_name' => $request->customer_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'total_price' => 0,
                'status' => 'pending',
            ]);

            $total = 0;
            $itemsToInsert = [];

            foreach ($cart->items as $item) {
                $product = $item->product; // استخدم العلاقة المحملة مسبقاً (Eager Loading)

                // قفل المنتج للتأكد من الكمية
                $product->lockForUpdate();

                if ($product->quantity < $item->quantity) {
                    throw new \Exception("عذراً، الكمية غير كافية للمنتج: {$product->name}");
                }

                $currentSalePrice = $product->sale_price;
                $currentCostPrice = $product->cost_price;
                $subTotal = $currentSalePrice * $item->quantity;
                $total += $subTotal;

                // تجهيز مصفوفة الـ Bulk Insert مع الأسعار الجديدة
                $itemsToInsert[] = [
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'company_id' => $order->company_id, // مهم جداً للـ SaaS
                    'quantity'   => $item->quantity,
                    'sale_price' => $currentSalePrice, // تجميد سعر البيع
                    'cost_price' => $currentCostPrice, // تجميد سعر التكلفة للارباح
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // تخصيم الكمية مباشرة
                $product->decrement('quantity', $item->quantity);
            }

            // 4. تنفيذ الإدخال الجماعي (أداء عالي جداً)
            OrderItem::insert($itemsToInsert);

            // 5. تحديث إجمالي الطلب النهائي
            $order->update(['total_price' => $total]);

            // 6. تنظيف السلة (تغيير الحالة بدل الحذف نهائياً أفضل للـ Data Analysis)
            $cart->items()->delete();
            $cart->update(['status' => 'completed']);

            // إرجاع الرابط للانتقال إليه بعد نجاح الـ Transaction
            return redirect()->away($this->generateWhatsAppLink($order));
        });
    }
    private function generateWhatsAppLink($order)
    {
        $order->load('items.product');

        $message = "طلب جديد:\n";
        $message .= "الاسم: {$order->customer_name}\n";
        $message .= "الموبايل: {$order->phone}\n";
        $message .= "العنوان: {$order->address}\n\n";

        foreach ($order->items as $item) {
            $message .= $item->product->name . " × " . $item->quantity . "\n";
        }

        $message .= "\nالإجمالي: {$order->total_price} جنيه";

        $phone = "01288306919"; // رقم الشركة

        return "https://wa.me/$phone?text=" . urlencode($message);
    }
}
