<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::get();

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $products = Product::get();
        $suppliers = Supplier::get();

        return view('purchases.create', compact('products', 'suppliers'));
    }

    public function store(PurchaseRequest $request)
    {
		$product = Product::find($request['product_id']);

		$units_received = $product->units_received + $request['quantity'];

		$units_in_stock = $product->units_in_stock + $request['quantity'];
		
		$product->update([
			'units_in_stock' => $units_in_stock,
			'units_received' => $units_received
		]);

        Purchase::create($request->only([
            'product_id',
            'quantity',
            'purchase_date',
            'supplier_id'
        ]));

        session()->flash('message', 'Purchase data added successfully');

        return redirect('purchase');
    }

    public function edit(Purchase $purchase)
    {
        $products = Product::get();
        $suppliers = Supplier::get();

        return view('purchases.edit', compact('purchase', 'products', 'suppliers'));
    }

    public function update(PurchaseRequest $request, Purchase $purchase)
    {
        $product = Product::find($request['product_id']);

		$units_received = $product->units_received + ($request['quantity'] - $purchase['quantity']);

		$units_in_stock = $product->units_in_stock + ($request['quantity'] - $purchase['quantity']);
		
		$product->update([
			'units_in_stock' => $units_in_stock,
			'units_received' => $units_received
		]);

        $purchase->update($request->only([
            'product_id',
            'quantity',
            'purchase_date',
            'supplier_id'
        ]));

        session()->flash('message', 'Purchase data successfully updated');

        return redirect('purchase');
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();

        session()->flash('message', 'Purchase data successfully deleted');

        return redirect('purchase');
    }
}
