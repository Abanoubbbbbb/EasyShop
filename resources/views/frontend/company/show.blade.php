@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100">

    <!-- 🏢 Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-14 shadow-lg">

        <div class="container mx-auto text-center">

            <img src="{{ $company->logo ? asset('storage/' . $company->logo) : 'https://via.placeholder.com/120' }}"
                class="w-24 h-24 mx-auto rounded-full border-4 border-white shadow-lg object-cover bg-white mb-4">

            <h1 class="text-3xl font-bold">
                {{ $company->name }}
            </h1>

            <p class="text-sm opacity-80 mt-1">
                🛍️ Welcome to {{ $company->name }} Store
            </p>

        </div>
    </div>

    <!-- 📦 Content -->
    <div class="container mx-auto py-10 px-4">

        @forelse($categories as $category)

        <!-- 🧩 Category Header -->
        <div class="mt-10 mb-4 flex justify-between items-center">

            <h2 class="text-lg font-bold text-gray-800">
                📦 {{ $category->name }}
            </h2>

        </div>

        <!-- 🛍️ Products Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">

            @forelse($category->products as $product)

            <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">

                <!-- IMAGE -->
                <div class="aspect-square bg-gray-100">

                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}"
                        class="w-full h-full object-cover">

                </div>

                <!-- INFO -->
                <div class="p-3">

                    <h3 class="text-sm font-semibold text-gray-800 truncate">
                        {{ $product->name }}
                    </h3>

                    <!-- 💰 PRICE -->
                    <div class="mt-1">

                        @if($product->discount > 0)

                        <p class="text-[10px] line-through text-gray-400">
                            {{ number_format($product->sale_price) }} EGP
                        </p>

                        <p class="text-green-600 font-bold text-sm">
                            {{ number_format($product->sale_price - $product->discount) }} EGP
                        </p>

                        @else

                        <p class="text-green-600 font-bold text-sm">
                            {{ number_format($product->sale_price) }} EGP
                        </p>

                        @endif

                    </div>

                    <!-- STOCK -->
                    <div class="flex justify-between items-center mt-2">

                        @if($product->quantity > 0)
                        <span class="text-[10px] bg-green-100 text-green-700 px-2 py-1 rounded-full">
                            In Stock
                        </span>
                        @else
                        <span class="text-[10px] bg-red-100 text-red-600 px-2 py-1 rounded-full">
                            Out
                        </span>
                        @endif

                    </div>

                    <!-- VIEW BUTTON -->
                    <a href="{{ route('product.show', $product->id) }}"
                        class="block text-center mt-3 bg-blue-600 hover:bg-blue-700 text-white text-sm py-1.5 rounded-lg">

                        View

                    </a>

                </div>

            </div>

            @empty

            <div class="col-span-4 text-center text-gray-400 py-6">
                😢 No products in this category
            </div>

            @endforelse

        </div>

        @empty

        <div class="text-center text-gray-500 py-10">
            😢 No categories available yet
        </div>

        @endforelse

    </div>

</div>

@endsection