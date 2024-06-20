<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductsServiceStoreRequest;
use App\Models\Api_Utils;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductServiceAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get all products
        try {
            $products = Product::all();
            if ($products) {
                return Api_Utils::success($products, 'Products found successfully', 200);
            } else {
                return Api_Utils::error([
                    'Products not found', 404
                ]);
            }
        } catch (\Exception $e) {
            return Api_Utils::error([
                'error' => $e->getMessage(),
                'message' => 'Failed to get products',
                'status' => 500
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductsServiceStoreRequest $request)
    {
        //store a product
        try {
            $poducts = Product::create($request->all());
            return Api_Utils::success([$poducts, 'Product stored successfully', 200]);
        } catch (\Exception $e) {
            return Api_Utils::error([
                'error' => $e->getMessage(),
                'message' => 'Failed to store product',
                'status' => 500
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //show a product
        try {
            $product = Product::find($id);
            if ($product) {
                return Api_Utils::success($product, 'Product found successfully', 200);
            } else {
                return Api_Utils::error(
                    'Product not found',
                    404
                );
            }
        } catch (\Exception $e) {
            return Api_Utils::error([
                'error' => $e->getMessage(),
                'message' => 'Failed to get product',
                'status' => 500
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductsServiceStoreRequest $request, $id)
    {
        //update a product
        try {
            $product = Product::find($id);
            if ($product) {
                $product->update($request->all());
                return Api_Utils::success($product, 'Product updated successfully', 200);
            } else {
                return Api_Utils::error(
                    'Product not found',
                    404
                );
            }
        } catch (\Exception $e) {
            return Api_Utils::error([
                'error' => $e->getMessage(),
                'message' => 'Failed to update product',
                'status' => 500
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete a product
        try {
            $product = Product::find($id);
            if ($product) {
                $product->delete();
                return Api_Utils::success($product, 'Product deleted successfully', 200);
            } else {
                return Api_Utils::error(
                    'Product not found',
                    404
                );
            }
        } catch (\Exception $e) {
            return Api_Utils::error([
                'error' => $e->getMessage(),
                'message' => 'Failed to delete product',
                'status' => 500
            ]);
        }
    }
}
