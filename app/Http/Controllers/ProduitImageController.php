<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProduitImage;
use Illuminate\Support\Facades\Storage;

class ProduitImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $image = ProduitImage::findOrFail($id);
        // supprimer le fichier de l'image du stockage
        Storage::disk('public')->delete($image->path);
        // supprimer l'enregistrement de l'image de la base de données
        $image->delete();
        return response()->json(['message'=>'Image supprimée avec succès']);
    }
}
