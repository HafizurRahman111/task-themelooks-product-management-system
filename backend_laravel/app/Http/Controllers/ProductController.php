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
            $products = ProductDetail::with('variations')
                ->paginate(8);

            $products->map(function ($product) {
                $product->image = asset('assets/images/' . $product->image);
                return $product;
            });

            return response()->json([
                'status' => 'success',
                'data' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'total' => $products->total(),
                ],
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
        // Retrieve the product with its variations
        $product = ProductDetail::with('variations')->find($id);

        // Check if the product exists
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        // Apply image URL mapping for the product image
        $product->image = asset('assets/images/' . $product->image);

        // Set default prices for the main product
        $this->setDefaultPrices($product);

        // Apply default prices for variations if they exist
        if ($product->variations && $product->variations->count() > 0) {
            $product->variations->each(function ($variation) {
                $this->setDefaultPrices($variation);
            });
        }

        // Return the product details in the response
        return response()->json([
            'success' => true,
            'product' => $product,
        ]);
    }

    private function setDefaultPrices($product)
    {
        // Set default values for price-related attributes if not set
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
    public function destroy(string $id)
    {
        //
    }


    public function getProducts(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
        ]);

        $search = $request->input('search', '');

        $products = ProductDetail::select('id', 'name', 'sku')
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->get();

        return response()->json($products);
    }


}
