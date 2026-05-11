<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>

        <div class="p-6 bg-white rounded-xl shadow">

            <h2 class="text-xl font-bold mb-4">
                Welcome to Dashboard
            </h2>

            <!-- Go to Store -->
            <a href="{{ url('/companies') }}"
                class="inline-block bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">

                🛍️ Go to Store
            </a>

        </div>
    </div>
</x-app-layout>