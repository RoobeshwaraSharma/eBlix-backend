<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $payment = Payment::create([
            'cardType' => $request->cardType,
            'cardNumber' => $request->cardNumber,
            'cardholderName' => $request->cardholderName,
            'expirationDate' => $request->expirationDate,
            'cvv' => $request->cvv,
            'status' => 'success' // Set status to success
        ]);

        return response()->json(['message' => 'Payment successful', 'payment' => $payment]);
    }
}

