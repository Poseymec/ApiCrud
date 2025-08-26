<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProduitResource;
use Illuminate\Http\Request;
use App\Models\Produit;
use App\Http\Requests\StoreProduitRequest;
use App\Http\Requests\UpdateProduitRequest;
use App\Models\ProduitImage;
use Illuminate\Support\Facades\DB;

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
     * afficher un poduit en foction de son Id.
     */
    public function show(string $id)
    {
        $produit= Produit::with('images')->findOrFail($id);

        return new ProduitResource($produit);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProduitRequest $request, string $id)
    {
        //on fait une transsction pour eviter incoherence s'il ya erreur
        DB:: beginTransection();

        try{
            //recuperer les produit
            $produit = Produit::findOrFail($id);
        
            //mettre a jour les infirmation des produits

            $produit->update($request->validated());

            //gerer les images

            if($request->hasFile('images')){
                foreach($request->file('images') as $image)
                {
                    $path = $image->store('produit_images','public');

                    ProduitImage::create([
                        'produit-id'=>$produit->id,
                        'url'=> $path
                    ]);
                }

            }
                
            DB::commit();

            // retourner dans la resource produit 

            return new ProduitResource($produit->laod('images'));
            
        } catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message'=>'Erreur lors de la mise a jour du produit',
                'error'=>$e->getMessage()
            ],500);
            

        }
    }

    /**
     * supprimer un produit de la base de données.
     */
    public function destroy(string $id)
    {
       $produit = Produit:: findOrFail($id);

       $produit->delete();
         return response()->json(['message'=>'produit supprimé avec succés']);
    }
}
