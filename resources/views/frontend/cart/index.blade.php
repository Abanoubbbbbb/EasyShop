@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 py-10">

    <div class="container mx-auto px-4">

        <!-- Messages -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 p-4 rounded-xl mb-6 text-center font-semibold shadow">
            ✅ {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-300 text-red-800 p-4 rounded-xl mb-6 text-center font-semibold shadow">
            ❌ {{ session('error') }}
        </div>
        @endif

        <h1 class="text-3xl font-bold mb-8 text-gray-800">
            🛒 Your Cart
        </h1>

        @if(!$cart || $cart->items->count() == 0)

        <!-- Empty Cart -->
        <div class="text-center py-20 bg-white rounded-2xl shadow">
            <p class="text-gray-500 text-lg">Your cart is empty 😢</p>

            <a href="{{ route('home') }}"
                class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:opacity-90 transition">
                Go Shopping
            </a>
        </div>

        @else

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- 🛍️ Items -->
            <div class="lg:col-span-2 space-y-5">

                @php $total = 0; @endphp

                @foreach($cart->items as $item)

                @php
                $price = $item->product->sale_price;
                $subtotal = $price * $item->quantity;
                $total += $subtotal;
                @endphp

                <div class="bg-white p-5 rounded-2xl shadow flex gap-5 items-center hover:shadow-xl transition">

                    <!-- Image -->
                    <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/150' }}"
                        class="w-24 h-24 object-cover rounded-xl border">

                    <!-- Info -->
                    <div class="flex-1">
                        <h2 class="font-bold text-lg text-gray-800">
                            {{ $item->product->name }}
                        </h2>

                        <p class="text-green-600 font-semibold mt-1">
                            {{ number_format($price) }} EGP
                        </p>

                        <p class="text-sm text-gray-400 mt-1">
                            Subtotal: {{ number_format($subtotal) }} EGP
                        </p>
                    </div>

                    <!-- Quantity -->
                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-2">
                        @csrf

                        <input type="number"
                            name="quantity"
                            value="{{ $item->quantity }}"
                            min="1"
                            class="w-16 border rounded-lg p-1 text-center">

                        <button class="bg-green-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-green-600">
                            Update
                        </button>
                    </form>

                    <!-- Remove -->
                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                        @csrf
                        <button class="text-red-500 hover:text-red-700 text-lg">
                            ✖
                        </button>
                    </form>

                </div>

                @endforeach

            </div>

            <!-- 💰 Summary -->
            <div class="bg-white p-6 rounded-2xl shadow h-fit sticky top-10">

                <h2 class="text-xl font-bold mb-4 text-gray-800">
                    Order Summary
                </h2>

                <div class="flex justify-between mb-2 text-gray-600">
                    <span>Subtotal</span>
                    <span>{{ number_format($total) }} EGP</span>
                </div>

                <div class="flex justify-between mb-2 text-gray-600">
                    <span>Shipping</span>
                    <span>Free</span>
                </div>

                <hr class="my-3">

                <div class="flex justify-between font-bold text-lg">
                    <span>Total</span>
                    <span>{{ number_format($total) }} EGP</span>
                </div>

                <!-- Checkout Section -->
                <div class="mt-10 p-6 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        إتمام الطلب
                    </h3>

                    <form action="{{ route('checkout') }}" method="POST" id="checkout-form" class="space-y-4">
                        @csrf

                        <!-- الاسم -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الاسم بالكامل</label>
                            <input type="text" name="customer_name" required
                                placeholder="أدخل اسمك الثلاثي"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition duration-200 placeholder:text-gray-400">
                        </div>

                        <!-- الهاتف -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
                            <input type="tel" name="phone" required
                                placeholder="01xxxxxxxxx"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition duration-200">
                        </div>

                        <!-- العنوان -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">عنوان التوصيل</label>
                            <textarea name="address" required rows="2"
                                placeholder="المحافظة، المدينة، اسم الشارع، رقم العقار"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition duration-200"></textarea>
                        </div>

                        <!-- زرار الدفع -->
                        <button type="submit" id="checkout-btn"
                            class="w-full mt-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 rounded-xl font-bold text-lg hover:shadow-lg hover:scale-[1.01] active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2">
                            <span>تأكيد الطلب عبر واتساب</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- سكريبت بسيط لمنع الضغط المتكرر (أمان إضافي) -->
                <script>
                    document.getElementById('checkout-form').onsubmit = function() {
                        const btn = document.getElementById('checkout-btn');
                        btn.disabled = true;
                        btn.innerHTML = 'جاري إرسال طلبك...';
                        btn.classList.add('opacity-70', 'cursor-not-allowed');
                    };
                </script>

                </form>

            </div>

        </div>

        <!-- JS -->
        <script>
            const form = document.getElementById('checkout-form');

            if (form) {
                form.addEventListener('submit', function() {
                    const btn = document.getElementById('checkout-btn');
                    btn.disabled = true;
                    btn.innerText = 'Processing...';
                });
            }
        </script>

        @endif

    </div>

</div>

@endsection