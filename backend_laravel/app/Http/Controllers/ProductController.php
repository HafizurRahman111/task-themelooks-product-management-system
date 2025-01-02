<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductDetail;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = ProductDetail::with('variations')->get();

            $products->map(function ($product) {
                // Calculate discounted price
                $discounted_price = $product->selling_price - ($product->selling_price * $product->discount / 100);

                // Calculate tax
                $tax_amount = ($discounted_price * $product->tax) / 100;

                $product->discounted_price = $discounted_price;
                $product->tax_amount = $tax_amount;

                $product->image = asset('assets/images/' . $product->image);
                return $product;
            });

            return response()->json([
                'status' => 'success',
                'data' => $products,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching product data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = ProductDetail::with('variations')->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        $product->image = asset('assets/images/' . $product->image);

        $this->setDefaultPrices($product);

        if ($product->variations && $product->variations->count() > 0) {
            $product->variations->each(function ($variation) {
                $this->setDefaultPrices($variation);
            });
        }

        return response()->json([
            'success' => true,
            'product' => $product,
        ]);
    }

    private function setDefaultPrices($product)
    {
        $product->selling_price = $product->selling_price ?? 'N/A';
        $product->purchase_price = $product->purchase_price ?? 'N/A';
        $product->discount = $product->discount ?? 'N/A';
        $product->tax = $product->tax ?? 'N/A';
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $product = ProductDetail::findOrFail($id);

            $product->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully.',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Product not found.',
            ], 404);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the product.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function getProducts(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
        ]);

        $search = $request->input('search', '');

        $products = ProductDetail::select('id', 'name', 'sku', 'image')
            ->when($search, function ($query) use ($search) {
                return $query->where('name', '=', $search)
                    ->orWhere('sku', '=', $search);
            })
            ->get()
            ->map(function ($product) {
                $product->image = asset('assets/images/' . $product->image);
                return $product;
            });

        return response()->json([
            'products' => $products,
        ]);
    }


}
