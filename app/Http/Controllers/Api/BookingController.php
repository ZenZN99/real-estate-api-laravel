<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'Customer') {
            return response()->json(['error' => 'Only customers can book properties'], 403);
        }

        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:properties,id',
            'date_from' => 'required|date|after_or_equal:today',
            'date_to' => 'required|date|after:date_from',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $property = Property::find($request->property_id);
        if ($property->status !== 'available') {
            return response()->json(['error' => 'Property is not available'], 400);
        }

        $booking = Booking::create([
            'property_id' => $request->property_id,
            'user_id' => $user->id,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => 'Booking created successfully',
            'booking' => $booking
        ]);
    }

    public function myBookings(Request $request)
    {
        $user = $request->user();

        $bookings = Booking::with('property')->where('user_id', $user->id)->get();

        return response()->json($bookings);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'Customer') {
            return response()->json(['error' => 'Foribdden'], 403);
        }

        $query = Booking::with(['property', 'user']);

        if ($user->role === 'Agent') {
            $query->whereHas('property', function ($q) use ($user) {
                $q->where('agent_id', $user->id);
            });
        }

        $bookings = $query->get();

        return response()->json($bookings);
    }

    public function updateStatus(Request $request, $id)
    {
        $user = $request->user();
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        if ($user->role === 'Customer') {
            return response()->json(['error' => 'Foribdden'], 403);
        }

        if ($user->role === 'Agent' && $booking->property->agent_id !== $user->id) {
            return response()->json(['error' => 'You can only update bookings for your properties'], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $booking->status = $request->status;
        $booking->save();

        return response()->json([
            'success' => 'Booking status updated successfully',
            'booking' => $booking
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        if ($user->role !== 'Admin') {
            return response()->json(['error' => 'Forbidden. Admins only'], 403);
        }

        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        $booking->delete();

        return response()->json(['success' => 'Booking deleted successfully']);
    }
}
