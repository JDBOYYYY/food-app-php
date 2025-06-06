<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Http\Requests\CreateAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
// REMOVE THIS LINE: use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth; // Keep this for Auth::user()

class AddressController extends Controller
{
    public function __construct()
    {
        // Now this should work
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        // If we have auth:
        // $user = Auth::user(); // Or $request->user() if middleware is applied
        // if ($user) {
        //     $addresses = $user->addresses()->get();
        //     return AddressResource::collection($addresses);
        // }

        // Temporary: For testing without full auth, allow fetching all or by userId param
        // Since 'index' is excepted from 'auth:sanctum', Auth::user() might be null here
        // unless a token is optionally provided.
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
        $user = Auth::user();
        // $user = Auth::user(); // Also works

        if (!$user) {
            // This case should ideally not be hit if 'auth:sanctum' middleware is active for store
            // and a valid token is not provided. The middleware would deny access first.
            return response()->json(['message' => "User not authenticated."], Response::HTTP_UNAUTHORIZED);
        }
        $address = $user->addresses()->create($request->validated());

        return (new AddressResource($address))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Address $address)
    {
        // TODO: Authorization - user can only see their own address or admin can see any
        // $this->authorize('view', $address); // Requires a Policy
        $address->load('user');
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