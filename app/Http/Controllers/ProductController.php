<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(ProductRequest $request)
    {
        Product::create($request->only([
            'name',
            'units_in_stock',
            'minimum_required',
            'price'
        ]));

        session()->flash('message', 'Product data successfully added');

        return redirect('product');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->only([
            'name',
            'units_in_stock',
            'minimum_required',
            'price'
        ]));

        session()->flash('message', 'Product data successfully updated');

        return redirect('product');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        session()->flash('message', 'Product data successfully deleted');

        return redirect('product');
    }

    public function stock()
    {
        $products = Product::get();

        return view('stocks.index', compact('products'));
    }
}
