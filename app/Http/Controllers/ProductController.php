<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\SearcProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Categorie;
use App\Models\Product;
use App\Services\FcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule as ValidationRule;
use GPBMetadata\Google\Api\Auth;
use GuzzleHttp\Psr7\Query;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(SearcProductRequest $request)

    {
        $query = Product::query();

        $query->when($request->price, function ($query) use ($request) {
            $query->where('price', '=', $request->price);
        });

        $query->when($request->name, function ($query) use ($request) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        });


        $query->when($request->filled('category'), function ($query) use ($request) {
            $query->where('categorie_id', $request->category);
        });

        $query->when($request->date, function ($query) use ($request) {
            $query->where('created_at', $request->date);
        });

        $query->when($request->date_from && $request->date_to, function ($query) use ($request) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        });

        $query->when($request->priceFrom && $request->priceTo, function ($query) use ($request) {
            $query->whereBetween('price', [$request->priceFrom, $request->priceTo]);
        });


        $query->when($request->sortby && $request->sortorder, function ($query) use ($request) {
            $query->orderBy($request->sortby, $request->sortorder);
        });

        // Global copes for products whose quantity is available   
        $allAvailableProducts = Product::quantity()->paginate(5);


        // $allAvailableProducts = $query->paginate(5);

        return ProductResource::collection($allAvailableProducts)
            ->response()
            ->setStatusCode(200);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create(ProductRequest $request) {}


    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {

        $validated = $request->validated();

        //Start Uploading images 

        if ($request->hasFile('image_url')) {
            $image = $request->file('image_url');
            $path = Storage::disk('public')->put('products_image', $image);
            // $path = $image->store('Products_images', 'public');  logic 2 -> store   طريقة 2
            $validated['image_url'] = $path;
        }
        //End Uploading images 

        $product = Product::create($validated);

        // return FcmService::notify('Notification', ' Product added successfully', [""]);

        return (new ProductResource($product))
            ->additional([
                'meta' =>  [
                    'success' => true,
                    'message' => 'Product added successfully.',
                ],
            ])
            ->response()
            ->setStatusCode(201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(200);
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
    public function update(ProductRequest $request, string $id)
    {
        $product = Product::findOrFail($id);

        $vaildated = $request->validated();
        $product->update($vaildated);

        // return response()->json($product, 200);
        return (new ProductResource($product))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $product = Product::findOrFail($id);
        Product::destroy($id);
        return (new ProductResource($product))
            ->additional(
                [
                    'message' => 'Product deleted successfully',
                ],
            )
            ->response()
            ->setStatusCode(200);
    }
}
