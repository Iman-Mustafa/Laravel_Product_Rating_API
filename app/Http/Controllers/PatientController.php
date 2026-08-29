<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PatientController extends Controller
{
    /**
     * Register a new patient by forwarding request to external hospital endpoint.
     */
    public function registerPatient(Request $request)
    {
        $payload = [
            'Sponsor_ID'        => (string) $request->input('Sponsor_ID', '1'),
            'Patient_Name'      => (string) $request->input('Patient_Name', 'ngenzi ngenzi'),
            'Date_Of_Birth'     => (string) $request->input('Date_Of_Birth', '2022-07-02'),
            'Gender'            => (string) $request->input('Gender', 'Male'),
            'Visit_Type_ID'     => (string) $request->input('Visit_Type_ID', '1'),
            'Type_Of_Check_In'  => (string) $request->input('Type_Of_Check_In', '1'),
            'branchId'          => (string) $request->input('branchId', '1'),
            'Employee_ID'       => (string) $request->input('Employee_ID', '46'),
            'pf3'               => $request->input('pf3', null),
            'Diceased'          => (string) $request->input('Diceased', 'no'),
            'Referral_Status'   => $request->input('Referral_Status', null),
        ];

        try {
            $response = Http::timeout(15)->post('http://41.188.172.204:3033/patient-registration', $payload);

            if ($response->successful()) {
                $data = $response->json();
                $checkInDateAndTime = $data['Check_In_Date_And_Time'] 
                    ?? ($data['data']['Check_In_Date_And_Time'] ?? now()->toDateTimeString());

                return response()->json([
                    'message'                => 'Patient registered successfully',
                    'Check_In_Date_And_Time' => $checkInDateAndTime,
                    'response_data'          => $data
                ], 200);
            }

            return response()->json([
                'status'  => 'error',
                'message' => 'External registration failed',
                'details' => $response->body()
            ], $response->status() ?: 500);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unable to reach external server: ' . $e->getMessage(),
            ], 500);
        }
    }
}
