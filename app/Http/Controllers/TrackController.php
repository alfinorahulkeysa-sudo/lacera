<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class TrackController extends Controller
{
    public function index()
    {
        return view('track');
    }

    public function search(Request $request)
    {
        $data = $request->validate([
            'reference' => 'required|string'
        ]);

        $ref = $data['reference'];
        // Remove '#' if the user accidentally pastes it
        $ref = ltrim(trim($ref), '#');

        $order = Order::with('items.product')
                ->where('order_number', $ref)
                ->orWhere('payment_reference', $ref)
                ->first();

        return view('track', ['order' => $order, 'reference' => $ref]);
    }
}
