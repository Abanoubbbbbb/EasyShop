@extends('layouts.app')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">

    <div class="bg-white w-full max-w-md p-8 rounded-2xl shadow-lg text-center border">

        <!-- Icon -->
        <div class="text-6xl mb-4">
            🚫
        </div>

        <!-- Title -->
        <h1 class="text-2xl font-bold text-red-600">
            Subscription Expired
        </h1>

        <!-- Message -->
        <p class="text-gray-600 mt-3 leading-relaxed">
            Your subscription has ended.
            <br>
            Please renew your plan to continue using the system.
        </p>

        <!-- Company Info -->
        @if(auth()->check() && auth()->user()->company)
        <div class="mt-5 text-sm text-gray-500 bg-gray-50 p-3 rounded-lg">
            <p><strong>Company:</strong> {{ auth()->user()->company->name }}</p>

            @if(auth()->user()->company->subscription_ends_at)
            <p>
                <strong>Expired At:</strong>
                {{ auth()->user()->company->subscription_ends_at }}
            </p>
            @endif
        </div>
        @endif

        <!-- Action Button -->
        <div class="mt-6">

            <a href="#"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl transition">
                Renew Subscription
            </a>

        </div>

        <!-- Back (optional) -->
        <div class="mt-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700">
                Back to Login
            </a>
        </div>

    </div>

</div>

@endsection