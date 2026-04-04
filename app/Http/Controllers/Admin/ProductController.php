<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('screens.admin.products.index', compact('products'));
    }


    public function toggleActive(Request $request)
    {
        $request->validate(['id' => 'required|exists:products,id']);
        $product = Product::findOrFail($request->id);
        $product->is_active = !($product->is_active ?? true);
        $product->save();
        return response()->json([
            'success' => true,
            'message' => $product->is_active ? 'Product activated.' : 'Product deactivated.',
        ]);
    }

    public function create()
    {
        $categories = ProductCategory::all();
        return view('screens.admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp',
            'category_id' => 'required|exists:product_categories,id',
        ]);

        $mainImagePath = null;
        if ($request->hasFile('image')) {
            $mainImagePath = $request->file('image')->store('products/main', 'public');
        }

        if ($request->hasFile('images') && count($request->file('images')) > 5) {
            return response()->json([
                'success' => false,
                'message' => 'You can upload a maximum of 5 gallery images.',
            ], 422);
        }

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'image' => $mainImagePath,
            'category_id' => $request->category_id,
            'description' => $request->description,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'images' => $path,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Product Created Successfully',
            'redirect' => route('products.index'),
        ]);
    }

    public function edit($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $categories = ProductCategory::get();
        return view('screens.admin.products.edit', compact('product', 'categories'));
    }


    public function update(Request $request, $slug)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp',
            'category_id' => 'required|exists:product_categories,id',
        ]);

        $product = Product::where('slug', $slug)->firstOrFail();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products/main', 'public');
            $product->image = $path;
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
        ]);

        $existingCount = $product->images()->count();
        $newCount = $request->hasFile('images') ? count($request->file('images')) : 0;

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products/gallery', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'images' => $path,
                ]);
            }
        }

        if (($existingCount + $newCount) > 5) {
            return response()->json([
                'message' => 'Total gallery images cannot exceed 5.'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product,
            'redirect' => route('products.index'),
        ]);
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $categories = ProductCategory::get();
        return view('screens.admin.products.show', compact('product', 'categories'));
    }

    public function destroy($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product Delete Successfully'
        ]);
    }

    public function deleteGalleryImage($id)
    {
        $image = ProductImage::findOrFail($id);

        // file delete
        if (Storage::disk('public')->exists($image->images)) {
            Storage::disk('public')->delete($image->images);
        }

        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image removed successfully'
        ]);
    }
}
