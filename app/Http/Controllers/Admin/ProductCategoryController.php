<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\ProductCategory;


class ProductCategoryController extends Controller
{
    public function index()
    {
        $productCategories = ProductCategory::all();
        return view('screens.admin.product-categories.index', compact('productCategories'));
    }
    public function create()
    {
        return view('screens.admin.product-categories.create');
    }
    public function store(Request $request)

    {
        $slug = $request->slug
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        $request->merge([
            'slug' => $slug
        ]);
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'slug' => [
                    'required',
                    'string',
                    Rule::unique('product_categories', 'slug'),
                ],
            ],
            [
                'slug.unique'   => 'This category already exists. Please use a different name.',
                'slug.required' => 'The category slug is required.',
            ]
        );

        $productCategory = ProductCategory::create([
            'name'     => $request->name,
            'slug' => $request->slug,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product category created successfully',
            'redirect' => route('product-categories.index')
        ]);
    }
    public function update(Request $request, ProductCategory $productCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        $productCategory->update([
            'name' => $request->name,
            // slug intentionally untouched
        ]);
    
        return response()->json([
            'success' => true,
            'data' => $productCategory,
            'message' => 'Product category updated successfully',
        ]);
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();
    
        return response()->json([
            'success'  => true,
            'message'  => 'Product category deleted successfully',
            'data'     => $productCategory,
            'redirect' => route('product-categories.index'),
        ]);
    }
}
