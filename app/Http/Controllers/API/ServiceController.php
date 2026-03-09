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
        $branchId   = $request->user()->branch_id;
        $activeOnly = $request->boolean('active', true);
        $services   = $this->serviceRepository->getBranchServices($branchId, $activeOnly);
        return response()->json($services);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'turnaround_hours'  => 'nullable|integer|min:1',
            'is_active'         => 'nullable|boolean',
        ]);

        $data              = $request->all();
        $data['branch_id'] = $request->user()->branch_id;
        $data['is_active'] = $request->boolean('is_active', true);

        $service = $this->serviceRepository->create($data);
        return response()->json($service, 201);
    }

    public function show(string $id)
    {
        $service = $this->serviceRepository->find($id);
        if (!$service) return response()->json(['message' => 'Service not found'], 404);
        return response()->json($service);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'             => 'sometimes|required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'sometimes|required|numeric|min:0',
            'turnaround_hours' => 'nullable|integer|min:1',
            'is_active'        => 'nullable|boolean',
        ]);

        $service = $this->serviceRepository->update($id, $request->all());
        if (!$service) return response()->json(['message' => 'Service not found'], 404);

        return response()->json($service);
    }

    public function destroy(string $id)
    {
        $deleted = $this->serviceRepository->delete($id);
        if (!$deleted) return response()->json(['message' => 'Service not found'], 404);

        return response()->json(['message' => 'Service deleted']);
    }
}
