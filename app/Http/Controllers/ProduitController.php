<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProduitResource;
use Illuminate\Http\Request;
use App\Models\Produit;
use App\Http\Requests\StoreProduitRequest;
use App\Http\Requests\UpdateProduitRequest;

class ProduitController extends Controller
{
    /**
     * afficher les produits .
     */
    public function index()
    {
        return ProduitResource::collection(Produit::with('images','categories')->get());
    }

    /**
     * enregister un produit dans la base avec ses image.
     */
    public function store(StoreProduitRequest $request)
    {
        $produit = Produit::create($request->validated());

        // enregister les images du produit
        if($request->hasFile('images')){
            foreach($request->file('images') as $file){
                $path = $file->store('produit_images','public');
                $produit->produit_images()->create(['path'=>$path]);
            }
        }
        return new ProduitResource($produit->laod('produit_images','categories'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
