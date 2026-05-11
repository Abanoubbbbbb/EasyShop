@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-10">

    <h1 class="text-3xl font-bold mb-8">🛍️ Categories</h1>

    @forelse($categories as $category)

    <div class="mb-10">

        <!-- اسم الكاتيجوري -->
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">
            {{ $category->name }}
        </h2>

        <!-- المنتجات -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            @forelse($category->products as $product)

            <a href="{{ url('/product/' . $product->id) }}">

                <div class="bg-white p-4 rounded-xl shadow hover:shadow-xl transition">

                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/200' }}"
                        class="w-full h-40 object-cover rounded-lg mb-3">

                    <h3 class="font-bold text-lg">
                        {{ $product->name }}
                    </h3>

                    <p class="text-green-600 font-semibold mt-1">
                        {{ number_format($product->price) }} EGP
                    </p>

                </div>

            </a>

            @empty
            <p class="text-gray-400">No products</p>
            @endforelse

        </div>

    </div>

    @empty
    <p>No categories found</p>
    @endforelse

</div>

@endsection