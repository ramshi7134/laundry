<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\Interfaces\CustomerRepositoryInterface;

class CustomerController extends Controller
{
    protected $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function index(Request $request)
    {
        $branchId = $request->user()->branch_id;
        $customers = $this->customerRepository->getBranchCustomers($branchId);
        return response()->json($customers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:customers,phone',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['branch_id'] = $request->user()->branch_id;

        $customer = $this->customerRepository->create($data);
        return response()->json($customer, 201);
    }

    public function show(string $id)
    {
        $customer = $this->customerRepository->find($id);
        if (!$customer) return response()->json(['message' => 'Not found'], 404);
        return response()->json($customer);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|unique:customers,phone,' . $id,
            'email' => 'nullable|email',
            'address' => 'nullable|string',
        ]);

        $customer = $this->customerRepository->update($id, $request->all());
        if (!$customer) return response()->json(['message' => 'Not found'], 404);
        
        return response()->json($customer);
    }
}
