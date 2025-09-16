<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact ;
use App\Http\Resources\ContactResource;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreContactRequest;


class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():JsonResponse
    {
        $contacts = ContactResource::collection(Contact::all());

        return response()->json([
            'status'=>'success',
            'data'=>$contacts
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request): JsonResponse
    {
        $contacts = Contact::create($request->validated());
        return response()->json([
            'message'=>'conctact cree avec succes',
            'data'=> new ContactResource($contacts)
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id):JsonResponse
    {
        $contact = Contact::findOrFail($id);
        return response()->json( new ContactResource($contact),200);
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
    public function destroy(string $id) :JsonResponse
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return response()->json([
            'message'=>'information supprimée avec succes'
        ],200);
    }

  /***
   * Basculer le statut d'un contact entre "lu" et "non lu"
   */
    public function toggleStatus(Contact $contact): JsonResponse
    {
        $newStatus = $contact->status === 'read' ? 'unread' : 'read';
        $contact->update(['status' => $newStatus]);

        return response()->json([
            'message' => 'Statut du contact mis à jour avec succès',
            'data' => new ContactResource($contact->fresh())
        ]);
    }
}

