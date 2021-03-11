<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::get();

    	return view('orders.index', compact('orders'));
    }

    public function create()
    {
    	$products = Product::get();
    	$customers = Customer::get();

    	return view('orders.create', compact('products', 'customers'));
    }

    public function store(OrderRequest $request)
    {   
        
		$product = Product::find($request['product_id']);
        
		$units_on_order = $product->units_on_order + $request['quantity'];
        
		$units_in_stock = $product->units_in_stock - $request['quantity'];
        
        if($units_in_stock < 0){
            session()->flash('message-error', 'Order data failed');
        } else {
            Order::create($request->all());
            $product->update([
                'units_in_stock' => $units_in_stock,
                'units_on_order' => $units_on_order
            ]);

            session()->flash('message', 'Order data added successfully');
        }

        return redirect('order');
    }

    public function edit(Order $order)
    {
        $products = Product::get();
        $customers = Customer::get();

        return view('orders.edit', compact('order', 'products', 'customers'));
    }

    public function update(OrderRequest $request, Order $order)
    {
        $order->update($request->only([
            'product_id',
            'quantity',
            'order_date',
            'customer_id'
        ]));

        session()->flash('message', 'Order data successfully updated');

        return redirect('order');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        session()->flash('message', 'Order data successfully deleted');

        return redirect('order');
    }
}
