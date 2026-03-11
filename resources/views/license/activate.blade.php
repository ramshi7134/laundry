@extends('layouts.app')

@section('title', 'Activate License')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-lg border border-red-100">
            <div class="text-center">
                <svg class="mx-auto h-16 w-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900 tracking-tight">
                    Activation Requiredhi
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Your POS software license has expired or is invalid. Please enter a valid license key to continue.
                </p>
            </div>

            <form class="mt-8 space-y-6" action="{{ route('license.verify') }}" method="POST">
                @csrf
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="license_key" class="sr-only">License Key</label>
                        <input id="license_key" name="license_key" type="text" required
                            class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500 focus:z-10 sm:text-sm uppercase"
                            placeholder="XXXX-XXXX-XXXX-XXXX">
                    </div>
                </div>

                @if (session('error'))
                    <p class="text-red-500 text-sm mt-1 text-center font-medium">{{ session('error') }}</p>
                @endif

                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors shadow-md hover:shadow-lg">
                        <span>Activate Software</span>
                    </button>
                </div>

                <div class="text-center mt-4">
                    <a href="#" class="text-sm font-medium text-gray-500 hover:text-gray-900">Need a new license?
                        Contact Support</a>
                </div>
            </form>
        </div>
    </div>
@endsection
