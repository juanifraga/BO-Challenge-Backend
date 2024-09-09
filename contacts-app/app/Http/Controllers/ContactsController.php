<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Contact;
use Illuminate\Support\Facades\Storage;


class ContactsController extends Controller
{
    public function index()
    {
        $contacts = Contact::all();
        return response()->json([
            'success' => true,
            'contacts' => $contacts,
        ]);
    }

    public function show($id)
    {
        $contact = Contact::find($id);

        if ($contact) {
            return response()->json($contact);
        }

        return response()->json(['error' => 'Contact not found'], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:15',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $file = $request->file('img');
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '_profile_picture.' . $extension;
        $path = $file->storeAs('public/uploads', $fileName);
        $url = Storage::url($path);
    
        $contact = Contact::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'profile_picture' => $url,
        ]);
    
        return response()->json([
            'success' => true,
            'contact' => $contact,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:15',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $contact = Contact::findOrFail($id);
    
        if ($request->has('name')) {
            $contact->name = $request->name;
        }
        if ($request->has('address')) {
            $contact->address = $request->address;
        }
        if ($request->has('email')) {
            $contact->email = $request->email;
        }
        if ($request->has('phone_number')) {
            $contact->phone_number = $request->phone_number;
        }
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_profile_picture.' . $extension;
            $path = $file->storeAs('public/uploads', $fileName);
            $url = Storage::url($path);
            $contact->img_url = $url;
        }
    
        $contact->save();
    
        return response()->json([
            'success' => true,
            'contact' => $contact,
        ], 200);
    }

    public function destroy($id)
    {
        $contact = Contact::find($id);

        if ($contact) {
            $contact->delete();
            return response()->json(['message' => 'Contact deleted']);
        }

        return response()->json(['error' => 'Contact not found'], 404);
    }

}