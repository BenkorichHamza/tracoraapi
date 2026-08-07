<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('mobile-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->with("contact")->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $token = $user->createToken('mobile-token')->plainTextToken;
        // $user->load('contact.warehouse');
        return response()->json([
            'user' => $user,
            'contact' => $user->contact,
            'warehouse' => $user->contact?->warehouse,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }

    public function linkContact(Request $request, User $user)
{
    $validated = $request->validate([
        'contact_id' => ['required', 'uuid', 'exists:contacts,id'],
    ]);

    $contact = Contact::findOrFail($validated['contact_id']);

    if (User::where('contact_id', $contact->id)->exists()) {
        return response()->json([
            'message' => 'This contact is already linked to a user'
        ], 422);
    }

    $user->contact_id = $contact->id;
    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'User linked to contact successfully',
        'data' => $user->load('contact')
    ]);
}

public function unlinkContact(User $user)
{
    // Optional: check if already linked
    if (!$user->contact_id) {
        return response()->json([
            'success' => false,
            'message' => 'User is not linked to any contact'
        ], 422);
    }

    $user->contact_id = null;
    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'User unlinked from contact successfully',
        'data' => $user->load('contact') // will be null
    ]);
}

}
