<?php

namespace App\Http\Controllers;

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
        $data = $request->validated();

        // Si c'est un fichier, on l'ajoute via notre méthode
        if ($request->input('type') === 'file') {
            $data['content'] = $this->ajoutFichier($request, 'content');
        }

        $element = SiteElement::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Élément créé avec succès.',
            'data' => new SiteElementResource($element)
        ], 201);
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
        $data = $request->validated();

        if ($request->input('type') === 'file' && $request->hasFile('content')) {
            $data['content'] = $this->ajoutFichier($request, 'content');
        }

        $siteElement->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Élément mis à jour avec succès.',
            'data' => new SiteElementResource($siteElement)
        ], 200);
    }

    public function destroy(SiteElement $siteElement): JsonResponse
    {
        $siteElement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Élément supprimé avec succès.',
        ], 204);
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

        // Stocker le fichier dans storage/app/public/site_elements
        $file->storeAs('public/site_elements', $fileName);

        // Retourner le chemin relatif à stocker dans la base
        return 'site_elements/' . $fileName;
    }
}
