<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsLetterRequest;
use Illuminate\Http\JsonResponse;
use App\Models\NewsLetters;
use App\Http\Resources\NewsLetterResource;
use Illuminate\Http\Request;

class NewsLetterController extends Controller
{
    public function index(): JsonResponse
    {
        $letters = NewsLetterResource::collection(NewsLetters::all());
        return response()->json([
            'status' => 'success',
            'data' => $letters
        ], 200);
    }

    public function store(StoreNewsLetterRequest $request): JsonResponse
    {
        try {
            $letter = NewsLetters::create($request->validated());
            return response()->json([
                'status' => 'success',
                'message' => 'Newsletter créée avec succès',
                'data' => new NewsLetterResource($letter)
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de la newsletter : ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        $letter = NewsLetters::findOrFail($id);
        return response()->json(new NewsLetterResource($letter), 200);
    }

    /**  public function update(Request $request, string $id): JsonResponse
    {
        $letter = NewsLetters::findOrFail($id);
        $letter->update($request->all());
        return response()->json(new NewsLetterResource($letter), 200);
    }*/

    public function destroy(string $id): JsonResponse
    {
        $letter = NewsLetters::findOrFail($id);
        $letter->delete();
        return response()->json(['message' => 'Information supprimée avec succès'], 200);
    }
}
