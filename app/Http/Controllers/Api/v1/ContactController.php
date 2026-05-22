<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ContactResource::collection(
            Contact::with(['user'])->latest()->paginate(20)
         );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'type' => ['nullable', 'string'],

            'first_name' => ['nullable', 'string', 'max:255'],

            'last_name' => ['nullable', 'string', 'max:255'],

            'company_name' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | Contact
            |--------------------------------------------------------------------------
            */

            'address' => ['nullable', 'string'],

            'phone' => ['nullable', 'string'],

            'fax' => ['nullable', 'string'],

            'fix' => ['nullable', 'string'],

            'code' => ['nullable', 'string'],

            /*
            |--------------------------------------------------------------------------
            | Financial
            |--------------------------------------------------------------------------
            */

            'due' => ['nullable', 'numeric'],

            'payment' => ['nullable', 'numeric'],

            /*
            |--------------------------------------------------------------------------
            | Algerian Identifiers
            |--------------------------------------------------------------------------
            */

            'nif' => ['nullable', 'string'],

            'nis' => ['nullable', 'string'],

            'nin' => ['nullable', 'string'],

            /*
            |--------------------------------------------------------------------------
            | Extra Data
            |--------------------------------------------------------------------------
            */

            'data' => ['nullable', 'array'],

            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        $validated['created_by'] = auth()->id();

        $contact = Contact::create(collect($validated)
        ->except('image')
        ->toArray());

         if ($request->hasFile('image')) {

            $contact
                ->addMediaFromRequest('image')
                ->toMediaCollection('contact');
            }

        return new ContactResource(
            $contact->load([
                'user',
                'createdBy',
                'media',
            ])
        );

    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        return new ContactResource(
            $contact->load([
                'user',
                'createdBy',
                'updatedBy',
            ])
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
       $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'type' => ['nullable', 'string'],

            'first_name' => ['nullable', 'string', 'max:255'],

            'last_name' => ['nullable', 'string', 'max:255'],

            'company_name' => ['nullable', 'string', 'max:255'],

            'image' => ['nullable', 'image', 'max:5120'],

            /*
            |--------------------------------------------------------------------------
            | Contact
            |--------------------------------------------------------------------------
            */

            'address' => ['nullable', 'string'],

            'phone' => ['nullable', 'string'],

            'fax' => ['nullable', 'string'],

            'fix' => ['nullable', 'string'],

            'code' => ['nullable', 'string'],

            /*
            |--------------------------------------------------------------------------
            | Financial
            |--------------------------------------------------------------------------
            */

            'due' => ['nullable', 'numeric'],

            'payment' => ['nullable', 'numeric'],

            /*
            |--------------------------------------------------------------------------
            | Algerian Identifiers
            |--------------------------------------------------------------------------
            */

            'nif' => ['nullable', 'string'],

            'nis' => ['nullable', 'string'],

            'nin' => ['nullable', 'string'],

            /*
            |--------------------------------------------------------------------------
            | Extra Data
            |--------------------------------------------------------------------------
            */

            'data' => ['nullable', 'array'],
        ]);

        $validated['updated_by'] = auth()->id();

        $contact->update(collect($validated)->except('image')->toArray());
if($request->hasFile('image')) {
$contact->clearMediaCollection('contact');
            $contact
                ->addMediaFromRequest('image')
                ->toMediaCollection('contact');
            }
        return new ContactResource(
            $contact->load([
                'user',
                'createdBy',
                'updatedBy',
            ])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->update([
            'deleted_by' => auth()->id(),
        ]);

        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact deleted successfully',
        ]);
    }
}
