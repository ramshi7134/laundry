<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CustomerWebController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'phone' => 'required|string',
            ]);

            $customer = Customer::where('phone', $request->phone)->first();

            if ($customer) {
                // In a real application, you might use OTP here
                // We're keeping it simple for demonstration
                session(['customer_id' => $customer->id]);
                return redirect()->route('customer.orders');
            }

            return back()->withErrors(['phone' => 'Phone number not found in our records.']);
        }

        return view('customer.login');
    }

    public function orders(Request $request)
    {
        $customerId = session('customer_id');

        if (!$customerId) {
            return redirect()->route('customer.login');
        }

        $customer = Customer::find($customerId);
        $orders = Order::where('customer_id', $customerId)->with(['items.service'])->orderBy('created_at', 'desc')->get();

        return view('customer.orders', compact('customer', 'orders'));
    }
}
