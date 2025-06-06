<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Http\Resources\PaymentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the payments.
     */
    public function index()
    {
        $payments = Payment::with('order')->paginate(15);
        return PaymentResource::collection($payments);
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'OrderId' => 'required|exists:Orders,Id',
            'PaymentDate' => 'sometimes|date',
            'Amount' => 'required|numeric',
            'PaymentMethod' => 'required|string|max:50',
            'TransactionId' => 'nullable|string|max:100',
            'Status' => 'required|string|in:' . implode(',', [
                Payment::STATUS_PENDING,
                Payment::STATUS_SUCCEEDED,
                Payment::STATUS_FAILED,
                Payment::STATUS_REFUNDED,
            ]),
        ]);

        $payment = Payment::create($validated);
        $payment->load('order');

        return (new PaymentResource($payment))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        $payment->load('order');
        return new PaymentResource($payment);
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'OrderId' => 'sometimes|exists:Orders,Id',
            'PaymentDate' => 'sometimes|date',
            'Amount' => 'sometimes|numeric',
            'PaymentMethod' => 'sometimes|string|max:50',
            'TransactionId' => 'nullable|string|max:100',
            'Status' => 'sometimes|string|in:' . implode(',', [
                Payment::STATUS_PENDING,
                Payment::STATUS_SUCCEEDED,
                Payment::STATUS_FAILED,
                Payment::STATUS_REFUNDED,
            ]),
        ]);

        $payment->update($validated);
        $payment->load('order');

        return new PaymentResource($payment);
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->noContent();
    }
}
