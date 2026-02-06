<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with('agent')->orderBy('created_at', 'DESC')->get();
        return response()->json($properties);
    }

    public function show($id)
    {
        $property = Property::with('agent')->findOrFail($id);
        return response()->json($property);
    }



public function store(Request $request)
{
    $user = $request->user();

    if (!in_array($user->role, ['Admin', 'Agent'])) {
        return response()->json(['error' => 'Forbidden'], 403);
    }

    $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'location' => 'required|string',
        'status' => 'nullable|in:available,sold,rented',
        'images' => 'nullable|array',
        'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        'agent_id' => $user->role === 'Admin'
            ? 'required|exists:users,id'
            : 'nullable',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    $images = [];

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('properties', 'public');
            $images[] = '/storage/' . $path;
        }
    }

    $property = Property::create([
        'title' => $request->title,
        'description' => $request->description,
        'price' => $request->price,
        'location' => $request->location,
        'status' => $request->status ?? 'available',
        'images' => $images, 
        'agent_id' => $user->role === 'Agent'
            ? $user->id
            : $request->agent_id,
    ]);

    return response()->json([
        'success' => true,
        'property' => $property
    ], 201);
}


public function update(Request $request, $id)
{
    $user = $request->user();
    $property = Property::find($id);

    if (!$property) {
        return response()->json(['error' => 'Property not found'], 404);
    }

    if ($user->role === 'Customer') {
        return response()->json(['error' => 'Foribdden'], 403);
    } elseif ($user->role === 'Agent' && $property->agent_id !== $user->id) {
        return response()->json(['error' => 'You can only edit your own properties'], 403);
    }

    $validator = Validator::make($request->all(), [
        'title' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'price' => 'nullable|numeric',
        'location' => 'nullable|string',
        'status' => 'nullable|in:available,sold,rented',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    $data = $request->only(['title', 'description', 'price', 'location', 'status']);

    if ($request->hasFile('images')) {
        $images = [];

        foreach ($request->file('images') as $image) {
            $path = $image->store('properties', 'public');
            $images[] = '/storage/' . $path;
        }

        $data['images'] = $images; 
    }

    $property->update($data);

    return response()->json([
        'success' => 'Property updated successfully',
        'property' => $property->fresh()
    ]);
}


    public function destroy(Request $request, $id)
{
    $user = $request->user();
    $property = Property::find($id);

    if (!$property) {
        return response()->json(['error' => 'Property not found'], 404);
    }

    if ($user->role === 'Customer') {
        return response()->json(['error' => 'Forbidden'], 403);
    }

    if ($user->role === 'Agent' && $property->agent_id !== $user->id) {
        return response()->json(['error' => 'You can only delete your own properties'], 403);
    }

    if (is_array($property->images)) {
        foreach ($property->images as $image) {
            $path = str_replace('/storage/', 'public/', $image);

            if (Storage::exists($path)) {
                Storage::delete($path);
            }
        }
    }

    $property->delete();

    return response()->json([
        'success' => true,
        'message' => 'Property deleted successfully'
    ]);
}
}
