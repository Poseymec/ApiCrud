<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorie;
use App\Http\Resources\CategorieResource;
use App\Http\Requests\StoreCategorieRequest;
use App\Http\Requests\UpdateCategorieRequest;

class CategorieController extends Controller
{
    /**
     * afficher la liste des categories
     */
    public function index()
    {
        return CategorieResource::collection(Categorie::with('produits')->get());
    }

    /**
     * enregister les categories dans la base de données.
     */
    public function store(StoreCategorieRequest $request)
    {
        $categorie =Categorie::create($request->validated());
        return response()->json(
            new CategorieResource($categorie),
            201
        );
           
    }

    /**
     * afficher une categorie specifique.
     */
    public function show(string $id)
    {
        $categorie = Categorie::with('produits')->findOrFail($id);
        return new CategorieResource($categorie);
    }

    /**
     * modifier les categories .
     */
    public function update(UpdateCategorieRequest $request, string $id)
    {   
        // on cherche la categorie a modifier
        $categorie = Categorie:: findOrFail($id);

        // on met a jour la categorie
        $categorie->update($request->validated());

        return response()->json(new CategorieResource($categorie->load('produits')), 200);
        
    }

    /**
     * spprimer une categorie de la base de données.
     */
    public function destroy(string $id)
    {
        $categorie = Categorie::findOrFail($id);
        $categorie->delete();
        return response()->json(['message'=>'categorie suppprimée avec succés'],204);
    }
}