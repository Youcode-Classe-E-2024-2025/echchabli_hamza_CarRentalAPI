<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarsController extends Controller
{
    // Get all cars
    public function getAll(int $param)
    {
        try {
            // Get the 'per_page' parameter from the request, default to 10 if not provided
           
    
            // Ensure perPage is a positive integer to avoid issues
            $perPage = is_numeric($param) && $param > 0 ? (int) $param : 10;
            
    
            return Car::paginate();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve cars'], 500);
        }
    }
    

    // Get car by ID
    public function getById($id)
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json(['message' => 'Car not found'], 404);
        }

        return response()->json($car);
    }

    // Create a new car
    public function create(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'license_plate'=> 'required|string|unique:cars',
            'price_per_day' => 'required|numeric',
        ]);

        $car = Car::create($validated);

        return response()->json($car, 201);
    }

    // Update an existing car
    public function update(Request $request, $id)
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json(['message' => 'Car not found'], 404);
        }

        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'model' => 'required|string|max:255',
             
            'price_per_day' => 'required|numeric',
        ]);

        $car->update($validated);

        return response()->json($car);
    }

    // Delete a car by ID
    public function delete($id)
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json(['message' => 'Car not found'], 404);
        }

        $car->delete();

        return response()->json(['message' => 'Car deleted successfully']);
    }


}
