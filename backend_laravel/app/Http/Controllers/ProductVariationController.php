<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductDetail;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductVariationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $variations = ProductVariation::all();

            $variations->map(function ($variation) {
                $variation->product_name = $variation->product->name;
                $variation->product_image = asset('assets/images/' . $variation->product->image);

                return $variation;
            });

            return response()->json([
                'status' => 'success',
                'data' => $variations,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching product variations.',
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
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'attributes' => 'required|array',
            'attributes.*.name' => 'required|string',
            'attributes.*.value' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $product = ProductDetail::find($request->product_id);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found.',
            ], 404);
        }

        $variationsData = [];
        if ($request->has('attributes') && is_array($request->attributes)) {
            foreach ($request->attributes as $attribute) {
                if (!empty($attribute['name']) && !empty($attribute['value'])) {
                    $variationsData[] = [
                        'product_id' => $product->id,
                        'purchase_price' => $request->purchase_price,
                        'selling_price' => $request->selling_price,
                        'variation_attributes' => json_encode([
                            'name' => $attribute['name'],
                            'value' => $attribute['value'],
                        ]),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($variationsData)) {
                ProductVariation::insert($variationsData);
            }
        }

        return response()->json([
            'message' => 'Product variations created successfully.',
            'product_id' => $product->id,
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
}
