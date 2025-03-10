<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Support\Facades\Auth;

class PaymentsController extends Controller
{
    public function getUserPaymentsById()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $payments = Payment::whereHas('rental', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return response()->json($payments, 200);
    }

    public function getPaymentByRentalId($rentalId)
    {
        $rental = Rental::where('user_id', Auth::id())->find($rentalId);

        if (!$rental) {
            return response()->json(['message' => 'Rental not found'], 404);
        }

        $payment = Payment::where('rental_id', $rentalId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        return response()->json($payment, 200);
    }

    public function createOne(Request $request)
    {
        $validatedData = $request->validate([
            'rental_id' => 'required|exists:rentals,id',
            'amount' => 'required|numeric',
            'method' => 'required|in:credit_card,paypal,cash',
            'status' => 'required|in:pending,completed,failed',
        ]);

        $rental = Rental::where('user_id', Auth::id())->find($validatedData['rental_id']);

        if (!$rental) {
            return response()->json(['message' => 'Unauthorized or rental not found'], 403);
        }

        $payment = Payment::create($validatedData);

        return response()->json($payment, 201);
    }

    public function updateOne(Request $request, $id)
    {
        $payment = Payment::whereHas('rental', function ($query) {
            $query->where('user_id', Auth::id());
        })->find($id);

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $validatedData = $request->validate([
            'amount' => 'required|numeric',
            'method' => 'required|in:credit_card,paypal,cash',
            'status' => 'required|in:pending,completed,failed',
        ]);

        $payment->update($validatedData);

        return response()->json($payment, 200);
    }

    public function deleteOne($id)
    {
        $payment = Payment::whereHas('rental', function ($query) {
            $query->where('user_id', Auth::id());
        })->find($id);

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $payment->delete();
        return response()->json(['message' => 'Payment deleted successfully'], 200);
    }
}
