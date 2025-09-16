<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreSiteElementRequest;
use App\Http\Requests\UpdateSiteElementRequest;
use App\Http\Resources\SiteElementResource;
use App\Models\SiteElement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteElementController extends Controller
{
    // ----------------------------
    // Méthodes publiques RESTful
    // ----------------------------

    public function index(): JsonResponse
    {
        $elements = SiteElement::with('siteElementCategorie')->get();
        return response()->json([
            'success' => true,
            'message' => 'Liste des éléments récupérée avec succès.',
            'data' => SiteElementResource::collection($elements)
        ], 200);
    }

    public function store(StoreSiteElementRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction(); // Démarrer une transaction

            // Créer l'élément avec les données validées
            $data = $request->validated();

            // Gestion fichier si type = file
            if ($request->input('type') === 'file' && $request->hasFile('content')) {
                $data['content'] = $this->ajoutFichier($request, 'content');
            }

            $siteElement = SiteElement::create($data);

            DB::commit(); // Valider la transaction

            return response()->json([
                'message' => 'Élément du site créé avec succès.',
                'data' => new SiteElementResource($siteElement->load('siteElementCategorie'))
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack(); // Annuler la transaction si erreur
            Log::error('Erreur création SiteElement: ' . $e->getMessage());

            return response()->json([
                'message' => 'Erreur lors de la création de l\'élément.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function show(SiteElement $siteElement): JsonResponse
    {
        $siteElement->load('siteElementCategorie');
        return response()->json([
            'success' => true,
            'message' => 'Élément récupéré avec succès.',
            'data' => new SiteElementResource($siteElement)
        ], 200);
    }

    public function update(UpdateSiteElementRequest $request, SiteElement $siteElement): JsonResponse
    {
        try {
            DB::beginTransaction(); // Démarrer une transaction

            $data = $request->validated();

            // Gestion du fichier si type = file et un nouveau fichier est uploadé
            if ($request->input('type') === 'file' && $request->hasFile('content')) {
                $data['content'] = $this->ajoutFichier($request, 'content');
            }

            $siteElement->update($data);

            DB::commit(); // Valider les changements

            return response()->json([
                'message' => 'Élément du site mis à jour avec succès.',
                'data' => new SiteElementResource($siteElement->load('siteElementCategorie'))
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Annuler les changements en cas d’erreur
            Log::error('Erreur mise à jour SiteElement: ' . $e->getMessage());

            return response()->json([
                'message' => 'Erreur lors de la mise à jour de l\'élément.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /*
        supprimer un element dans la base
    */


    public function destroy(SiteElement $siteElement): JsonResponse
    {
        try {
            DB::beginTransaction();

            $siteElement->delete();

            DB::commit();

            return response()->json([
                'message' => 'Élément supprimé avec succès.'
            ], 200); // ✅ 200 pour un succès avec message (204 ne permet pas de message)

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur suppression SiteElement: ' . $e->getMessage());

            return response()->json([
                'message' => 'Erreur lors de la suppression de l\'élément.',
                'error' => $e->getMessage()
            ], 500);
        }




}
   /*public function toggleStatus(SiteElement $element): JsonResponse
    {
        $newStatus = $element->status === 'active' ? 'inactive' : 'active';
        $element->update(['status' => $newStatus]);

        return response()->json([
            'message' => 'Statut de l\’élément mis à jour avec succès',
            'data' => new SiteElementResource($element)
        ]);
    }*/
    public function toggleStatus(SiteElement $element): JsonResponse
    {
        $newStatus = $element->status === 'active' ? 'inactive' : 'active';
        $element->update(['status' => $newStatus]);

        return response()->json([
            'message' => 'Statut de l\'élément mis à jour avec succès',
            'data' => new SiteElementResource($element->fresh())
        ]);
    }


    // ----------------------------
    // Méthode privée pour stocker un fichier
    // ----------------------------
    private function ajoutFichier(Request $request, string $field): string
    {
        $file = $request->file($field);

        // Générer un nom unique pour le fichier
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $fileName = $originalName . '_' . time() . '_' . uniqid() . '.' . $extension;

        // Stocker le fichier avec votre nom personnalisé
        $path = $file->storeAs('site_elements', $fileName, 'public');
        return $path;
    }
}
