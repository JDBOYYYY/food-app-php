<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Http\Requests\CreateAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $addresses = $user->addresses()->get();
        return AddressResource::collection($addresses);
    }

    public function store(CreateAddressRequest $request)
    {
        $user = $request->user();
        $address = $user->addresses()->create($request->validated());

        return (new AddressResource($address))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Request $request, Address $address)
    {
        $user = $request->user();
        if ((int) $address->UserId !== (int) $user->id) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }
        $address->load('user');
        return new AddressResource($address);
    }

    public function update(UpdateAddressRequest $request, Address $address)
    {
        $user = $request->user();
        if ((int) $address->UserId !== (int) $user->id) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }
        $address->update($request->validated());
        $address->load('user');
        return new AddressResource($address);
    }

    public function destroy(Request $request, Address $address)
    {
        $user = $request->user();
        if ((int) $address->UserId !== (int) $user->id) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }
        $address->delete();
        return response()->noContent();
    }
}