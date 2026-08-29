<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ContactController extends Controller
{
    public function sync(Request $request)
{
    // Validate and cast timestamp safely
$lastSyncMs = $request->input('last_sync_date');

$lastSyncDate = is_numeric($lastSyncMs) && (int)$lastSyncMs > 0
    ? Carbon::createFromTimestampMs((int) $lastSyncMs)
    : null;

$contacts = Contact::query()
    ->with([
        'media',
        'warehouse',
        'user.roles.permissions',
        'createdByUser',
        'updatedByUser',
        'deletedByUser',
    ])
    ->when($lastSyncDate, function ($query, $lastSyncDate) {
        $query->where('updated_at', '>=', $lastSyncDate);
            //   ->where(function ($subQuery) {
            //       // Handles both NULL updatedBy and non-current user updates
            //       $subQuery->whereNull('updatedBy')
            //                ->orWhere('updatedBy', '<>', auth()->id());
            //   });
    }, function ($query) {
        // Fallback baseline when no lastSyncDate is provided
        $query->where('updated_at', '>', Carbon::parse('2026-01-01'));
    })
    ->latest('updated_at')
    ->get();

return ContactResource::collection($contacts);
}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ContactResource::collection(
            Contact::with(['user.roles.permissions'])->latest()->paginate(20)
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
        // Ensure we explicitly validate the incoming UUID
        'id'          => ['required', 'uuid', 'unique:contacts,id'],
        'type'        => ['nullable', 'string'],
        'firstName'   => ['nullable', 'string', 'max:255'],
        'lastName'    => ['nullable', 'string', 'max:255'],
        'companyName' => ['nullable', 'string', 'max:255'],
        'warehouse_id' => ['nullable', 'uuid', 'exists:warehouses,id'],

        'address'     => ['nullable', 'string'],
        'phone'       => ['nullable', 'string'],
        'fax'         => ['nullable', 'string'],
        'fix'         => ['nullable', 'string'],
        'code'        => ['nullable', 'string'],

        'due'         => ['nullable', 'numeric'],
        'payment'     => ['nullable', 'numeric'],

        'nif'         => ['nullable', 'string'],
        'nis'         => ['nullable', 'string'],
        'nin'         => ['nullable', 'string'],
        'deletedAt' => ['nullable', 'numeric'],


        // Email is mandatory and must be unique only if type is employee
        'email'       => [
            Rule::requiredIf($request->type === 'employee'),
            'nullable',
            'email',
            Rule::unique('users', 'email')
        ],

        // Password is only required for employees
        'password'    => [
            Rule::requiredIf($request->type === 'employee'),
            'nullable',
            'string',
            'min:6'
        ],

        'data'        => ['nullable', 'array'],
        'image'       => ['nullable', 'image', 'max:5120'],
    ]);

    $token = null;

    // Wrap in a transaction to protect data integrity
    $contact = DB::transaction(function () use ($request, $validated, &$token) {

        $validated['createdBy'] = auth()->id();

        // 1. Create the Contact first (with the client-side UUID)
        $contact = Contact::create(
            collect($validated)
                ->except(['image', 'password'])
                ->toArray()
        );
        $contact->refresh(); // Ensure we have the latest state

        // 2. If it's an employee, create the User record pointing to this contact_id
        if ($request->type === 'employee') {
            $user = User::create([
                'contact_id' => $contact->id, // Linking the user to the contact
                'name'       => trim(($validated['firstName'] ?? '') . ' ' . ($validated['lastName'] ?? '')),
                'email'      => $validated['email'],
                'password'   => Hash::make($validated['password']),
            ]);

            // Issue the Sanctum token
            $token = $user->createToken('employee-auth-token')->plainTextToken;
        }

        // 3. Handle Media Library file attachment
        if ($request->hasFile('image')) {
            $contact->addMediaFromRequest('image')->toMediaCollection('contact');
        }

        return $contact;
    });

    // 4. Return the resource. If you have a 'user' relationship on Contact,
    // make sure it's defined as a hasOne() or hasMany() pointing to the User model.
    return (new ContactResource($contact->load(['user', 'media'])))
        ->additional([
            'meta' => [
                'token' => $token // Returns token string for employees, null for others
            ]
        ]);
}

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        return new ContactResource(
            $contact->load([
                'user'
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
        'firstName' => ['nullable', 'string', 'max:255'],
        'lastName' => ['nullable', 'string', 'max:255'],
        'companyName' => ['nullable', 'string', 'max:255'],
        'image' => ['nullable', 'image', 'max:5120'],
        'password' => [
            Rule::requiredIf($request->type === 'employee' && !$contact->user),
            'nullable',
            'string',
            'min:6'
        ],

        /*
        |--------------------------------------------------------------------------
        | Contact
        |--------------------------------------------------------------------------
        */

        'address' => ['nullable', 'string'],
        'phone' => ['nullable', 'string'],
        'email' => ['nullable', 'email'],
        'warehouse_id' => ['nullable', 'uuid', 'exists:warehouses,id'],
        'fax' => ['nullable', 'string'],
        'fix' => ['nullable', 'string'],
        'code' => ['nullable', 'string'],
        'deletedAt' => ['nullable', 'numeric'],
        'roles' =>['nullable','array'],
        'roles.*' => ['required', 'integer', 'exists:roles,id'],


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

    $validated['updatedBy'] = auth()->id();

    DB::transaction(function () use ($request, $contact, $validated) {
        // 1. Update contact attributes
        $contact->update(
            collect($validated)->except(['image', 'password'])->toArray()
        );
        if($validated['roles']){
             $u = $contact->user();
            if($u){
                $u->roles()->sync($validated["roles"]);
            }
        }

        // 2. Handle image upload via Spatie Media Library
        if ($request->hasFile('image')) {
            $contact->clearMediaCollection('contact');
            $contact->addMediaFromRequest('image')
                ->toMediaCollection('contact');
        }

        // 3. Handle Employee User record creation/update
        if ($request->type === 'employee') {
            $userData = array_filter([
                'email' => $validated['email'] ?? null,
                'password' => !empty($validated['password']) ? Hash::make($validated['password']) : null,
            ]);

            if (!empty($userData)) {
                $contact->user()->updateOrCreate(
                    ['contact_id' => $contact->id],
                    $userData
                );
                $user=$contact->user;
                $user?->tokens()->delete();
            }
        }
    });

    return new ContactResource($contact->load(['user']));
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->update([
            'deletedBy' => auth()->id(),
        ]);

        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact deleted successfully',
        ]);
    }
}
