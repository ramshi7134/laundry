<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\Interfaces\ServiceRepositoryInterface;

class ServiceController extends Controller
{
    protected $serviceRepository;

    public function __construct(ServiceRepositoryInterface $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function index(Request $request)
    {
        $branchId = $request->user()->branch_id;
        $services = $this->serviceRepository->getBranchServices($branchId);
        return response()->json($services);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $data = $request->all();
        $data['branch_id'] = $request->user()->branch_id;

        $service = $this->serviceRepository->create($data);
        return response()->json($service, 201);
    }

    public function show(string $id)
    {
        $service = $this->serviceRepository->find($id);
        if (!$service) return response()->json(['message' => 'Not found'], 404);
        return response()->json($service);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
        ]);

        $service = $this->serviceRepository->update($id, $request->all());
        if (!$service) return response()->json(['message' => 'Not found'], 404);
        
        return response()->json($service);
    }
}
