<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductCreateRequest;

class ProductController extends Controller
{
    public function index()
    {
        Gate::authorize('view', 'products');

        $products = Product::paginate();

        return ProductResource::collection($products);
    }

    public function show($id)
    {
        Gate::authorize('view', 'products');

        return new ProductResource(Product::find($id));
    }

    public function store(ProductCreateRequest $request)
    {
        Gate::authorize('edit', 'products');

        $product = Product::create($request->only('title', 'description', 'image', 'price'));

        return response($product, 200);
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('edit', 'products');

        $product = Product::find($id);

        $product->update($request->only('title', 'description', 'image', 'price'));

        return response($product, 200);
    }

    public function destroy($id)
    {
        Gate::authorize('edit', 'products');

        Product::destroy($id);

        return response(null, 200);
    }
}
