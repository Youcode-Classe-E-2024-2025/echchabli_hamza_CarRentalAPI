<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
/**
 * @OA\Schema(
 *     schema="Car",
 *     title="Car",
 *     description="Car model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="company", type="string", example="Toyota"),
 *     @OA\Property(property="model", type="string", example="Corolla"),
 *     @OA\Property(property="license_plate", type="string", example="ABC-123"),
 *     @OA\Property(property="price_per_day", type="number", format="float", example=50.00),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-10T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-03-10T12:00:00Z")
 * )
 */

class CarsController extends Controller
{
   /**
 * @OA\Get(
 *     path="/api/cars/pagin/{param}",
 *     summary="Get all cars with pagination",
 *     description="Retrieve a paginated list of cars",
 *     tags={"Cars"},
 *     @OA\Parameter(
 *         name="param",
 *         in="path",
 *         required=true,
 *         description="Number of items per page",
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Car")),
 *             @OA\Property(property="pagination", type="object",
 *                 @OA\Property(property="total", type="integer", example=100),
 *                 @OA\Property(property="per_page", type="integer", example=10),
 *                 @OA\Property(property="current_page", type="integer", example=1),
 *                 @OA\Property(property="last_page", type="integer", example=10),
 *                 @OA\Property(property="next_page_url", type="string", nullable=true, example=null),
 *                 @OA\Property(property="prev_page_url", type="string", nullable=true, example=null)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
public function getAll(int $param)
{
    try {
        // Ensure perPage is a positive integer to avoid issues
        $perPage = $param > 0 ? (int) $param : 10;

        // Fetch paginated cars
        return response()->json(Car::paginate($perPage));
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
