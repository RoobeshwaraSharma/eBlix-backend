<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Allorders;
use App\Models\User;
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
            'user_id' => 'required|exists:users,id',
        ]);
        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $orders = Allorders::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'user_id' => $request->user_id,
        ]);

        return response()->json(['allorders' => $orders], 201);
    }
    public function update(Request $request, $id)
    {
        $order = Allorders::find($id);
        $order->status = $request->status;
        $order->save();
        return response()->json(['message' => 'Order updated successfully'], 200);
    }

    public function index()
    {
        $orders = Allorders::with('user')->get();
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
