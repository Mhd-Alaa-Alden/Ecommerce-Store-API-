<?php

namespace App\Http\Controllers;

use App\Http\Requests\wishlistequest;
use App\Http\Requests\wishlistRequest;
use App\Http\Resources\WishlistResource;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource. */
    public function index()
    {
        //
        $wishlist = Wishlist::all();
        // return response()->json(['message' => 'All Products', 'wishlist' => $wishlist], 200);

        return  WishlistResource::collection($wishlist)
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Show the form for creating a new resource.  */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.  */
    public function store(wishlistRequest $request)
    {
        $validated =  $request->validated();
        $wishlist =  Wishlist::create($validated);
        // return response()->json(['message' => 'Successfully added to wishlist', 'wislist' => $wishlist], 200);

        return (new WishlistResource($wishlist))->additional([
            'message' => 'Successfully added to wishlist'
        ])
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Display the specified resource. */
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

    /**-
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $wishlist = Wishlist::findOrFail($id);
        Wishlist::destroy($id);

        return (new WishlistResource($wishlist))->additional([
            'message' => 'Deleted Wishlist'
        ])
            ->response()
            ->setStatusCode(200);
    }
}
