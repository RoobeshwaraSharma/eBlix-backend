<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Allorders;

class OrdersController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            // 'user_id' => 'required|exists:users,id',  // Ensure the user exists
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
        ]);

        $orders = Allorders::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        return response()->json(['allorders' => $orders], 201);
    }

    public function index()
    {
        $orders = Allorders::all();
        return response()->json($orders);
    }

    public function indexreport(Request $request)
    {
        $query = Allorders::query();

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $orders = $query->get();
        return response()->json($orders);
    }

    public function destroy($id)
    {
        $order = Allorders::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();
        return response()->json(['message' => 'Order deleted successfully'], 200);
    }

}
