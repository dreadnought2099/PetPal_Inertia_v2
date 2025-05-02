<?php

namespace App\Http\Controllers;

use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Pet;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PetController extends Controller
{
    public function index()
    {
        $pets = Pet::orderBy('created_at', 'desc')
            ->paginate(12)
            ->through(function ($pet) {
                return [
                    'id' => $pet->id,
                    'name' => $pet->name,
                    'age' => $pet->age,
                    'breed' => $pet->breed,
                    'allergies' => $pet->allergies,
                    'status' => $pet->status,
                    'description' => $pet->description,
                    'pet_profile_path' => $pet->pet_profile_path,
                    'sex' => $pet->sex,
                    'species_text' => $pet->species_text,
                    'vaccination_text' => $pet->vaccination_text,
                    'spayed_neutered' => $pet->spayed_neutered,
                ];
            });

        $user = Auth::user();

        return Inertia::render('Pets/Index', [
            'pets' => $pets,
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'roles' => $user->roles->pluck('name')->toArray(),
                ] : null,
            ],
        ]);
    }

    public function create()
    {
        // You can pass additional data for forms, etc.
        return Inertia::render('Pets/Create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'age' => 'required|integer|min:0|max:50',
                'breed' => 'required|string|max:100',
                'allergies' => 'nullable|string',
                'description' => 'nullable|string',
                'profile' => 'nullable|image|max:51200',
                'sex' => 'required',
                'species' => 'required|in:' . implode(',', [Pet::SPECIES_DOG, Pet::SPECIES_CAT]),
                'vaccination' => 'required|in:' . implode(',', [Pet::VACCINATION_NONE, Pet::VACCINATION_PARTIAL, Pet::VACCINATION_FULL]),
                'spayed_neutered' => 'required|boolean',
                'status' => 'sometimes|in:available,pending,adopted'
            ]);

            // Explicitly cast species, vaccination, spayed_neutered to integers
            $validated['species'] = (int) $request->input('species');
            $validated['vaccination'] = (int) $request->input('vaccination');
            $validated['spayed_neutered'] = (bool) $request->input('spayed_neutered');
        } catch (Exception $e) {
            return redirect()->route('pets.index')->with('error', 'Validation failed.');
        }

        if (!isset($validated['status'])) {
            $validated['status'] = 'available';
        }

        // Handle file upload for profile image
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $cleanPetName = Str::slug($validated['name']);
            $filename = $cleanPetName . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('pets/profile_photos', $filename, 'public');
            $validated['pet_profile_path'] = $path;
        }

        $validated['user_id'] = Auth::user()->id;

        try {
            $pet = Pet::create($validated);
            return redirect()->route('pets.index')->with('success', "Pet {$pet->name} was added successfully.");
        } catch (Exception $e) {
            return redirect()->route('pets.index')->with('error', 'Failed to add pet: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $pet = Pet::findOrFail($id);

        // Check if the user has permission to delete the pet listing
        if (!Auth::user()->can('delete pet listing')) {
            abort(403, 'Unauthorized action');
        }

        // Delete the pet
        $pet->delete();

        return redirect()->route('pets.index')->with('success', "Pet $pet->name deleted successfully");
    }

    public function update(Request $request, $id)
    {
        Log::info('Pet update request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'all' => $request->all(),
            'files' => $request->files->all(),
        ]);
        $pet = Pet::findOrFail($id);

        // Check if the user has permission to edit the pet listing
        if (!Auth::user()->can('edit pet listing')) {
            abort(403, 'Unauthorized action');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'age' => 'required|integer|min:0|max:50',
            'breed' => 'required|string|max:100',
            'allergies' => 'nullable|string',
            'description' => 'nullable|string',
            'profile' => 'nullable|image|max:51200',
            'sex' => 'sometimes|in:M,F',
            'species' => 'sometimes|integer|in:0,1',
            'vaccination' => 'required|integer|in:0,1,2',
            'spayed_neutered' => 'required|boolean',
        ]);

        // Explicitly cast species and vaccination to integers
        if (isset($validated['species'])) {
            $validated['species'] = (int) $validated['species'];
        }
        if (isset($validated['vaccination'])) {
            $validated['vaccination'] = (int) $validated['vaccination'];
        }

        if (isset($validated['spayed_neutered'])) {
            $validated['spayed_neutered'] = (bool) $request->input('spayed_neutered');
        }

        // Handle the profile image upload if provided
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $cleanPetName = Str::slug($validated['name']);
            $filename = $cleanPetName . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('pets/profile_photos', $filename, 'public');
            $validated['pet_profile_path'] = $path;
        }

        try {
            $pet->update($validated);
            return redirect()->route('pets.index')->with('success', "{$pet->name} updated successfully.");
        } catch (Exception $e) {
            return redirect()->route('pets.index')->with('error', 'Failed to update pet: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $pet = Pet::findOrFail($id);

        // Check if the user has permission to edit the pet listing
        if (!Auth::user()->can('edit pet listing')) {
            abort(403, 'Unauthorized action');
        }

        return Inertia::render('Pets/Edit', [
            'pet' => $pet
        ]);
    }
}
