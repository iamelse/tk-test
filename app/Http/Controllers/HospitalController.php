<?php

namespace App\Http\Controllers;

use App\Http\Requests\Hospital\StoreRequestHospital;
use App\Http\Requests\UpdateRequestHospital;
use App\Models\Hospital;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HospitalController extends Controller
{
    public function index(Request $request): View
    {
        $hospitals = Hospital::search($request->input('search'))
                              ->latestFirst()
                              ->paginate(10)
                              ->withQueryString();

        return view('pages.hospital.index', [
            'title' => 'Hospital Dashboard',
            'hospitals' => $hospitals
        ]);
    }

    public function store(StoreRequestHospital $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $hospital = Hospital::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Hospital created successfully!',
                'hospital' => $hospital
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create hospital.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateRequestHospital $request, Hospital $hospital): JsonResponse
    {
        try {
            $validated = $request->validated();
            $hospital->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Hospital updated successfully.',
                'data'    => $hospital
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update hospital.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Hospital $hospital)
    {
        try {
            $hospital->delete();

            return response()->json([
                'success' => true,
                'message' => 'Hospital deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete hospital.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
