<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class NotesController extends Controller
{
    public function index()
    {
        $notes = Note::where('user_id', Auth::id())
                 ->with('contact')
                 ->get();
                 
        return response()->json([
            'success' => true,
            'notes' => $notes,
        ], 200);
    }

    public function show($id)
    {
        $note = Note::where('user_id', Auth::id())
                    ->with('contact')
                    ->findOrFail($id);

        return response()->json([
            'success' => true,
            'note' => $note,
        ], 200);
    }


    public function storeForContact(Request $request, $contactId)
    {
        $validatedData = $request->validate([
            'content' => 'required|string',
        ]);

        $note = Note::create([
            'user_id' => Auth::id(),
            'contact_id' => $contactId,
            'content' => $validatedData['content'],
        ]);

        return response()->json([
            'success' => true,
            'note' => $note,
        ], 201);
    }
}