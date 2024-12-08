<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function getProductCount()
    {
        $productCount = Product::count();
        return response()->json(['count' => $productCount]);
    }

    public function index()
    {
        try {
            $products = Product::all();
            foreach ($products as $product) {
                $product->image_url = $product->image ? url('storage/images/' . $product->image) : null;
            }
            return response()->json(['products' => $products]);
        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            return response()->json(['error' => 'There was an error fetching the products'], 500);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'upload' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $product = new Product();
            $product->fill($validatedData);

            if ($request->hasFile('upload')) {
                $file = $request->file('upload');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/images', $filename);
                $product->image = $filename;
            }

            $product->save();
            $product->image_url = $product->image ? url('storage/images/' . $product->image) : null;

            return response()->json(['product' => $product], 201);
        } catch (\Exception $e) {
            Log::error('Error storing product: ' . $e->getMessage());
            return response()->json(['error' => 'There was an error storing the product'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'upload' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $product = Product::findOrFail($id);
            $product->fill($validatedData);

            if ($request->hasFile('upload')) {
                if ($product->image) {
                    Storage::delete('public/images/' . $product->image);
                }

                $file = $request->file('upload');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/images', $filename);
                $product->image = $filename;
            }

            $product->save();
            $product->image_url = $product->image ? url('storage/images/' . $product->image) : null;

            return response()->json(['product' => $product]);
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return response()->json(['error' => 'There was an error updating the product'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            if ($product->image) {
                Storage::delete('public/images/' . $product->image);
            }

            $product->delete();
            return response()->json(['message' => 'Product deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return response()->json(['error' => 'There was an error deleting the product'], 500);
        }
    }
}
