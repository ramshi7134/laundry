@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Welcome, {{ $customer->name }}!</h1>
            <p class="mt-2 text-sm text-gray-600">Here are your recent laundry orders.</p>
        </div>
        <a href="{{ route('customer.login') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">Sign out</a>
    </div>

    @if($orders->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            <h3 class="mt-4 text-xl font-medium text-gray-900">No orders found</h3>
            <p class="mt-1 text-gray-500">We couldn't find any orders linked to your phone number.</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white shadow-sm overflow-hidden rounded-2xl border border-gray-100 transition-all hover:shadow-md">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center flex-wrap gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Order Number</p>
                            <p class="text-lg font-bold text-gray-900">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Date</p>
                            <p class="text-md  text-gray-900">{{ $order->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Status</p>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'washing' => 'bg-blue-100 text-blue-800',
                                    'drying' => 'bg-indigo-100 text-indigo-800',
                                    'ironing' => 'bg-purple-100 text-purple-800',
                                    'ready' => 'bg-green-100 text-green-800',
                                    'delivered' => 'bg-gray-100 text-gray-800',
                                ];
                                $colorClass = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $colorClass }} capitalize">
                                {{ $order->status }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-500 mb-1">Total</p>
                            <p class="text-xl font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                        </div>
                    </div>
                    
                    <div class="px-6 py-5">
                        <ul class="divide-y divide-gray-100">
                            @foreach($order->items as $item)
                                <li class="py-3 flex justify-between">
                                    <div class="flex items-center">
                                        <span class="font-medium text-gray-900">{{ $item->service->name }}</span>
                                        <span class="ml-2 text-sm text-gray-500">x{{ $item->quantity }}</span>
                                    </div>
                                    <div class="font-medium text-gray-700">
                                        ${{ number_format($item->total, 2) }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
