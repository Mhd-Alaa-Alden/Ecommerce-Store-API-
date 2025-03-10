<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        // $userId = $request->user()->orders;
        $userId = $request->user()->id;

        $order = Order::where('user_id', $userId)->get();

        return  OrderResource::collection($order)
            ->response()
            ->setStatusCode(200);
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

    public function store(OrderRequest $request)
    {
        $userId = $request->user()->id;
        $cartItems = $request->validated();
        $cartItems['user_id'] = $userId;
        $totalPrice = 0;
        $cartItems['total_price'] = $totalPrice;

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            if ($product->stock_quantity < $item['quantity']) {
                return response()->json(['error' => 'Product not available in sufficient quantity'], 400);
            }
            $itemPrice = $product->price * $item['quantity'];
            $totalPrice += $itemPrice;
        }

        $order = Order::create($cartItems);

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            Order_item::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price * $item['quantity'],
            ]);

            $product->decrement('stock_quantity', $item['quantity']);
        }

        $order->update(['total_price' => $totalPrice]);


        return (new OrderResource($order))->additional([
            'meta' => [
                'message' => 'Order created successfully',
            ]
        ])
            ->response()
            ->setStatusCode(201);
    }



    /**
     * Display the specified resource. */
    public function show(string $id)
    {

        $order = Order::findOrFail($id);
        // $orderdate = $order->created_at;

        return (new OrderResource($order))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Show the form for editing the specified resource. */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([

            'status' => 'required|in:pending,shipped,completed,cancelled'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return (new OrderResource($order))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $order = Order::findOrFail($id);
        Order::destroy($id);

        // return response()->json(['message' => 'Deleted order']);
        return (new OrderResource($order))->additional([
            'message' => 'Deleted order'
        ])
            ->response()
            ->setStatusCode(200);
    }
}
