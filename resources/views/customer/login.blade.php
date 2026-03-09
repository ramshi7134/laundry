@extends('layouts.app')

@section('title', 'Customer Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 tracking-tight">
                Welcome to LaundryPOS
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Track your laundry orders easily
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="{{ route('customer.login') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="phone" class="sr-only">Phone Number</label>
                    <input id="phone" name="phone" type="text" required class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="Enter your registered phone number (e.g., 123-456-7890)">
                </div>
            </div>

            @error('phone')
                <p class="text-red-500 text-sm mt-1 text-center">{{ $message }}</p>
            @enderror

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-md hover:shadow-lg">
                    <span>Login & View Orders</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
