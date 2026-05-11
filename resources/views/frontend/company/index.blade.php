@extends('layouts.app')

@section('content')

<div class="container mx-auto py-10">

    <h1 class="text-2xl font-bold mb-6">🏢 Companies</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        @foreach($companies as $company)

        <a href="{{ route('company.show', $company->slug) }}"
            class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition">

            <h2 class="font-bold text-lg">{{ $company->name }}</h2>

            <p class="text-gray-500 text-sm mt-1">
                Click to view products
            </p>

        </a>
        <div class="bg-red-500 text-white p-10">
            TEST TAILWIND
        </div>

        @endforeach

    </div>

</div>

@endsection