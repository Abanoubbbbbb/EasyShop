@extends('layouts.app')

@section('content')

@php
$price = $product->sale_price;

$finalPrice = $product->discount > 0
? $price - $product->discount
: $price;
@endphp

<div class="min-h-screen bg-gray-100 py-12">

    <div class="container mx-auto px-4">

        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-xl mb-6 text-center font-semibold shadow">
            {{ session('success') }}
        </div>
        @endif

        <!-- Card -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden grid md:grid-cols-2 gap-10 items-center">

            <!-- Image -->
            <div class="bg-gray-50 flex items-center justify-center p-6 relative">

                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/500' }}"
                    class="w-full max-w-md h-80 object-cover rounded-2xl shadow-md hover:scale-105 transition duration-300">

                <!-- Badge -->
                @if($product->quantity > 0)
                <span class="absolute top-5 left-5 bg-green-500 text-white px-3 py-1 rounded-full text-xs">
                    Available
                </span>
                @else
                <span class="absolute top-5 left-5 bg-red-500 text-white px-3 py-1 rounded-full text-xs">
                    Out of Stock
                </span>
                @endif

            </div>

            <!-- Info -->
            <div class="p-10 flex flex-col justify-between">

                <div>

                    <!-- Name -->
                    <h1 class="text-3xl font-extrabold text-gray-800 leading-snug">
                        {{ $product->name }}
                    </h1>

                    <!-- PRICE -->
                    <div class="mt-4">

                        @if($product->discount > 0)

                        <p class="text-red-500 font-semibold">
                            🔻 Discount: {{ number_format($product->discount) }} EGP
                        </p>

                        <p class="text-gray-400 line-through">
                            {{ number_format($price) }} EGP
                        </p>

                        <p class="text-3xl text-green-600 font-bold">
                            Now: {{ number_format($finalPrice) }} EGP
                        </p>

                        @else

                        <p class="text-3xl text-green-600 font-bold">
                            {{ number_format($price) }} EGP
                        </p>

                        @endif

                    </div>

                    <!-- Stock -->
                    <div class="mt-3">
                        @if($product->quantity > 0)
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                            🟢 In Stock ({{ $product->quantity }})
                        </span>
                        @else
                        <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-sm font-semibold">
                            🔴 Out of Stock
                        </span>
                        @endif
                    </div>

                    <!-- Description -->
                    <p class="text-gray-600 mt-6 leading-relaxed">
                        {{ $product->description ?? 'This product has no description yet.' }}
                    </p>

                    <!-- Features -->
                    <div class="mt-6 space-y-2 text-sm text-gray-500">
                        <p>✔ Fast Delivery</p>
                        <p>✔ Secure Payment</p>
                        <p>✔ Quality Guaranteed</p>
                    </div>

                </div>

                <!-- Actions -->
                <div class="mt-10 space-y-5">

                    @if($product->quantity > 0)

                    <form action="{{ route('cart.add', $product->id) }}" method="POST" id="add-to-cart-form">
                        @csrf

                        <div class="flex gap-3 items-center">

                            <!-- Quantity -->
                            <div class="flex items-center border rounded-xl overflow-hidden">

                                <button type="button" onclick="decreaseQty()"
                                    class="px-3 bg-gray-200 hover:bg-gray-300">
                                    -
                                </button>

                                <input type="number"
                                    id="qty"
                                    name="quantity"
                                    value="1"
                                    min="1"
                                    max="{{ $product->quantity }}"
                                    class="w-16 text-center outline-none">

                                <button type="button" onclick="increaseQty()"
                                    class="px-3 bg-gray-200 hover:bg-gray-300">
                                    +
                                </button>

                            </div>

                            <!-- Button -->
                            <button type="submit"
                                id="add-btn"
                                class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600
                                       hover:opacity-90 text-white py-3 rounded-2xl font-semibold shadow-lg transition">

                                🛒 Add to Cart

                            </button>

                        </div>

                    </form>

                    @else

                    <button disabled
                        class="w-full bg-gray-400 text-white py-3 rounded-2xl font-semibold shadow">
                        ❌ Out of Stock
                    </button>

                    @endif

                    <!-- Back -->
                    <a href="javascript:history.back()"
                        class="block text-center text-gray-500 hover:text-gray-800 text-sm">
                        ← Back to products
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- JS -->
<script>
    function increaseQty() {
        let input = document.getElementById('qty');
        let max = parseInt(input.max);
        if (parseInt(input.value) < max) {
            input.value++;
        }
    }

    function decreaseQty() {
        let input = document.getElementById('qty');
        if (parseInt(input.value) > 1) {
            input.value--;
        }
    }

    document.getElementById('add-to-cart-form')?.addEventListener('submit', function() {
        let btn = document.getElementById('add-btn');
        btn.disabled = true;
        btn.innerText = 'Adding...';
    });
</script>

@endsection