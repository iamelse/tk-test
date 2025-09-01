<?php

namespace App\Http\Controllers;

use App\Http\Requests\Patient\StoreRequestPatient;
use App\Http\Requests\Patient\UpdateRequestPatient;
use App\Models\Hospital;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Request $request): View
    {
        $patients = Patient::with('hospital')
                   ->search($request->input('search'))
                   ->hospital($request->input('hospital_id'))
                   ->latestFirst()
                   ->paginate(10)
                   ->withQueryString();

        $hospitals = Hospital::all();

        return view('pages.patient.index', [
            'title' => 'Patient Dashboard',
            'patients' => $patients,
            'hospitals' => $hospitals,
            'showHospitalFilter' => true,
        ]);
    }

    public function store(StoreRequestPatient $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $patient = Patient::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Patient created successfully!',
                'patient' => $patient
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create patient.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateRequestPatient $request, Patient $patient): JsonResponse
    {
        try {
            $validated = $request->validated();
            $patient->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Patient updated successfully.',
                'data'    => $patient
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update patient.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Patient $patient): JsonResponse
    {
        try {
            $patient->delete();

            return response()->json([
                'success' => true,
                'message' => 'Patient deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete patient.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}