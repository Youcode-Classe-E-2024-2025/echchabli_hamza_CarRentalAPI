<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Rental;

class RentalsController extends Controller
{
    public function getUserRentals()
    {
        $user = Auth::user(); // Get the authenticated user

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $rentals = Rental::where('user_id', $user->id)->get();

        return response()->json($rentals, 200);
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $rental = Rental::create([
            'user_id' => Auth::id(),
            'car_id' => $validatedData['car_id'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'total_amount' => $validatedData['total_amount'],
            'status' => $validatedData['status'],
        ]);

        return response()->json($rental, 201);
    }



    public function update(Request $request, $id)
    {
        $rental = Rental::where('user_id', Auth::id())->find($id);

        if (!$rental) {
            return response()->json(['message' => 'Rental not found'], 404);
        }

        $validatedData = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $rental->update($validatedData);

        return response()->json($rental, 200);
    }

    public function delete($id)
    {
        $rental = Rental::where('user_id', Auth::id())->find($id);

        if (!$rental) {
            return response()->json(['message' => 'Rental not found'], 404);
        }

        $rental->delete();
        return response()->json(['message' => 'Rental deleted successfully'], 200);
    }

    
}
