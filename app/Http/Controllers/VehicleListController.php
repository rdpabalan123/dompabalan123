<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\VehicleList;

class VehicleListController extends Controller
{
    public function fetchAndStoreMGPSVehicles()
{
    // Define the API URL
    $apiUrl = 'https://api.gpstrack.global/vehicle/list/?hash=c80b6986e4458c875afa952e1ed05181';
    
    // Fetch data from the API
    $response = Http::get($apiUrl);
    
    // Check if the request was successful
    if ($response->successful()) {
        // Decode the JSON response
        $data = $response->json();
        
        // Extract specific details
        $vehicles = collect($data['list'])->map(function ($vehicle) {
            return [
                'tracker_id' => $vehicle['tracker_id'] ?? null,
                'tracker_label' => $vehicle['tracker_label'] ?? null,
                'label' => $vehicle['label'] ?? null,
            ];
        });
        
        try {
            // Clear existing records and insert new ones
            VehicleList::truncate(); // Optional: Truncate existing data if needed
            VehicleList::insert($vehicles->toArray());
            
            // Return response indicating success
            return response()->json(['success' => 'Data has been updated and stored successfully', 'data' => $vehicles]);
        } catch (\Exception $e) {
            // Return error response if insertion fails
            return response()->json(['error' => 'Failed to store data in database: ' . $e->getMessage()], 500);
        }
    } else {
        // Return error response if API fetch fails
        return response()->json(['error' => 'Failed to fetch data from API'], 500);
    }
}
}