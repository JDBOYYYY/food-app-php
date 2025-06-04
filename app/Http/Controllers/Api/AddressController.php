<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Http\Requests\CreateAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth; // For authenticated user

class AddressController extends Controller
{
    public function __construct()
    {
        // Placeholder: Apply Sanctum auth middleware once it's set up
        // $this->middleware('auth:sanctum')->except(['index', 'show']); // Example
    }

    public function index(Request $request)
    {
        // If we have auth:
        // $user = Auth::user();
        // $addresses = $user->addresses()->get();
        // return AddressResource::collection($addresses);

        // Temporary: For testing without full auth, allow fetching all or by userId param
        if ($request->has('userId')) {
             $addresses = Address::where('UserId', $request->input('userId'))->get();
        } else {
            // INSECURE - LISTS ALL ADDRESSES. FOR TESTING ONLY.
            $addresses = Address::with('user')->get(); // Eager load user for context
        }
        return AddressResource::collection($addresses);
    }

    public function store(CreateAddressRequest $request)
    {
        // ONCE AUTH IS SET UP:
        // $user = Auth::user();
        // $address = $user->addresses()->create($request->validated());

        // TEMPORARY FOR TESTING (assuming a user with ID 1 exists from seeder):
        $userIdToUse = 1; // This is a placeholder!
        $user = \App\Models\User::find($userIdToUse);
        if (!$user) {
            return response()->json(['message' => "Test User with ID {$userIdToUse} not found. Seed users first."], 404);
        }
        $address = $user->addresses()->create($request->validated());
        // OR if UserId is made fillable and you want to pass it (less ideal):
        // $address = Address::create(array_merge($request->validated(), ['UserId' => $userIdToUse]));


        return (new AddressResource($address))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Address $address)
    {
        // TODO: Authorization - user can only see their own address or admin can see any
        // $this->authorize('view', $address); // Requires a Policy
        $address->load('user'); // Eager load user for context
        return new AddressResource($address);
    }

    public function update(UpdateAddressRequest $request, Address $address)
    {
        // TODO: Authorization
        // $this->authorize('update', $address);
        $address->update($request->validated());
        $address->load('user');
        return new AddressResource($address);
    }

    public function destroy(Address $address)
    {
        // TODO: Authorization
        // $this->authorize('delete', $address);
        $address->delete();
        return response()->noContent();
    }
}