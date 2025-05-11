<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GalleryController extends Controller
{
    public function getGalleryCount()
    {
        $galleryCount = Gallery::count();
        return response()->json(['count' => $galleryCount]);
    }

    public function index()
    {
        try {
            $galleries = Gallery::all(['id', 'name', 'image', 'price', 'quantity', 'discount']);
            foreach ($galleries as $gallery) {
                $gallery->image_url = url('storage/images/gallery/' . $gallery->image);
                $gallery->discounted_price = $gallery->discountedPrice(); // Add the discounted price
            }
            return response()->json(['galleries' => $galleries]);
        } catch (\Exception $e) {
            Log::error('Error fetching galleries: ' . $e->getMessage());
            return response()->json(['error' => 'There was an error fetching the galleries'], 500);
        }
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'discount' => 'nullable|numeric|min:0|max:100', // Validate discount
        ]);

        try {
            $gallery = new Gallery();
            $gallery->name = $validatedData['name'];
            $gallery->price = $validatedData['price'];
            $gallery->quantity = $validatedData['quantity'];
            $gallery->discount = $validatedData['discount'] ?? 0; // Set discount if provided

            if ($request->hasFile('upload')) {
                $file = $request->file('upload');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/images/gallery'), $filename);
                $gallery->image = $filename;
            }

            $gallery->save();
            return response()->json(['gallery' => $gallery], 201);
        } catch (\Exception $e) {
            Log::error('Error storing gallery: ' . $e->getMessage());
            return response()->json(['error' => 'There was an error storing the gallery'], 500);
        }
    }


    public function show($id)
    {
        try {
            $gallery = Gallery::findOrFail($id);
            $gallery->image_url = url('storage/images/gallery/' . $gallery->image);
            return response()->json(['gallery' => $gallery]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gallery not found'], 404);
        }
    }

    // public function update(Request $request, $id)
    // {
    //     $validatedData = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'price' => 'required|numeric',
    //         'quantity' => 'required|integer',
    //         'upload' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //     ]);

    //     try {
    //         $gallery = Gallery::findOrFail($id);
    //         $gallery->name = $validatedData['name'];
    //         $gallery->price = $validatedData['price'];
    //         $gallery->quantity = $validatedData['quantity'];

    //         if ($request->hasFile('upload')) {
    //             // Delete the old image if it exists
    //             if ($gallery->image) {
    //                 $oldImagePath = public_path('storage/images/gallery/' . $gallery->image);
    //                 if (file_exists($oldImagePath)) {
    //                     unlink($oldImagePath);
    //                 }
    //             }

    //             // Move the new image
    //             $file = $request->file('upload');
    //             $filename = time() . '.' . $file->getClientOriginalExtension();
    //             $file->move(public_path('storage/images/gallery'), $filename);
    //             $gallery->image = $filename;
    //         }

    //         $gallery->save();
    //         $gallery->image_url = url('storage/images/gallery/' . $gallery->image);

    //         return response()->json(['gallery' => $gallery]);
    //     } catch (\Exception $e) {
    //         Log::error('Error updating gallery: ' . $e->getMessage());
    //         return response()->json(['error' => 'There was an error updating the gallery'], 500);
    //     }
    // }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'discount' => 'nullable|numeric|min:0|max:100',
            'upload' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $gallery = Gallery::findOrFail($id);
            $gallery->name = $validatedData['name'];
            $gallery->price = $validatedData['price'];
            $gallery->quantity = $validatedData['quantity'];
            $gallery->discount = $validatedData['discount'] ?? 0;

            if ($request->hasFile('upload')) {
                // Delete old image if exists
                if ($gallery->image) {
                    $oldImagePath = public_path('storage/images/gallery/' . $gallery->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Store new image
                $file = $request->file('upload');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/images/gallery'), $filename);
                $gallery->image = $filename;
            }

            $gallery->save();

            return response()->json(['gallery' => $gallery]);
        } catch (\Exception $e) {
            Log::error('Error updating gallery: ' . $e->getMessage());
            return response()->json(['error' => 'There was an error updating the gallery'], 500);
        }
    }




    public function destroy($id)
    {
        try {
            $gallery = Gallery::findOrFail($id);
            unlink(public_path('storage/images/gallery/' . $gallery->image));
            $gallery->delete();
            return response()->json(['message' => 'Image deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting gallery: ' . $e->getMessage());
            return response()->json(['error' => 'There was an error deleting the gallery'], 500);
        }
    }
}
