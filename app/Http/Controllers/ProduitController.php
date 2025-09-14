<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProduitRequest;
use App\Http\Requests\UpdateProduitRequest;
use App\Http\Resources\ProduitResource;
use App\Models\Produit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{


    //afficher tous les produits avec leurs categories et images
    public function index()
    {
        $produits = Produit::with(['images', 'categorie'])->get();
        return ProduitResource::collection($produits);
    }

    //enregistrer un nouveau produit en uilisant le form request StoreProduitRequest
    public function store(StoreProduitRequest $request): JsonResponse // un JsonResponse pour retourner une reponse json
    {

        //utiliser un try catch pour gerer les erreurs
        try {
            DB::beginTransaction(); // pour assuerer la coherence des donnees *soit tout reussi soit rien*

            $produit = Produit::create($request->validated()); // pour renvoyer les donnees valides du form request

            // Gérer l'upload des images si elles sont présentes
            if ($request->hasFile('images')) {
                $this->ajoutImages($request, $produit);
            }

            DB::commit();//valider les operations

            // Retourner une réponse JSON avec le produit créé
            return response()->json([
                'message' => 'Produit créé avec succès',
                'data' => new ProduitResource($produit->load(['images', 'categorie']))
            ], 201);

            //gerer les erreurs
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur création produit: ' . $e->getMessage());

            return response()->json([
                'message' => 'Erreur lors de la création du produit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //afficher un produit par son id avec ses images et sa categorie
    public function show(Produit $produit)
    {
        // Charger les relations images et catégorie
        $produit->load(['images', 'categorie']);

        // Retourner directement le resource
        return new ProduitResource($produit);
    }


    //mettre a jour un produit par son id en utilisant le form request UpdateProduitRequest
    public function update(UpdateProduitRequest $request, Produit $produit): JsonResponse
    {
        try {
            DB::beginTransaction();

            $produit->update($request->validated());

            if ($request->hasFile('images')) {
                $this->ajoutImages($request, $produit);
            }

            DB::commit();

            // Retourner directement la resource
            return response()->json(
                new ProduitResource($produit->load(['images', 'categorie']))
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur mise à jour produit: ' . $e->getMessage());

            return response()->json([
                'message' => 'Erreur lors de la mise à jour du produit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //supprimer un produit par son id
    public function destroy(Produit $produit): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Supprimer les fichiers du disque
            foreach ($produit->images as $image) {
                Storage::disk('public')->delete('produit_images/' . $image->image_path);
            }

            $produit->delete();

            DB::commit();

            return response()->json([
                'message' => 'Produit supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur suppression produit: ' . $e->getMessage());

            return response()->json([
                'message' => 'Erreur lors de la suppression du produit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

     //changer le statut d'un produit (active/inactive)
    public function toggleStatus(Produit $produit): JsonResponse
    {
        $newStatus = $produit->status === 'active' ? 'inactive' : 'active';
        $produit->update(['status' => $newStatus]);

        return response()->json([
            'message' => 'Statut du produit mis à jour avec succès',
            'data' => new ProduitResource($produit->load(['images', 'categorie']))
        ]);
    }

    // Méthode privée pour gérer l'upload d'images
    private function ajoutImages(Request $request, Produit $produit): void
    {
         // Vérifier si des fichiers ont été téléchargés
        if (!$request->hasFile('images')) {
            return;
        }

        $files = $request->file('images');
        $isFirstImage = $produit->images()->count() === 0;

        foreach ($files as $index => $file) {
            // Générer un nom unique pour le fichier
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = $originalName . '_' . time() . '_' . uniqid() . '.' . $extension;

            // Stocker le fichier
            $file->storeAs('public/produit_images', $fileName);

            // Créer l'enregistrement en base
            $produit->images()->create([
                'image_path' => $fileName,
                'is_cover' => $isFirstImage && $index === 0 // Premier fichier image  =  image_cover
            ]);
        }
    }
}
