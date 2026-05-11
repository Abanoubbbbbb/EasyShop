<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $company = Auth::user()?->company;

        if (!$company) {
            return redirect()->route('dashboard')
                ->with('error', 'No company found');
        }

        $categories = $company->categories()
            ->with(['products' => function ($q) {
                $q->latest()->limit(6); // 🔥 مهم للأداء (مش كل المنتجات)
            }])
            ->get();

        return view('frontend.home', compact('categories', 'company'));
    }
}
