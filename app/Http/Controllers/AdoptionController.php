<?php

namespace App\Http\Controllers;

use App\Models\Adoption;
use App\Models\Pet;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class AdoptionController extends Controller
{
    use AuthorizesRequests;

    public function index(): Response
    {
        $adoptions = Adoption::with('user', 'pet')->get();
        $pets = Pet::all();

        return Inertia::render('Adoptions/Index', [
            'adoptions' => $adoptions,
            'pets' => $pets,
        ]);
    }

    public function create($pet): Response
    {
        $selectedPet = Pet::find($pet);
        $pets = Pet::all();

        return Inertia::render('Adoptions/Create', [
            'selectedPet' => $selectedPet,
            'pets' => $pets,
        ]);
    }

    public function store(Request $request)
    {
        Log::info('Adoption Request Data:', $request->all());

        try {
            // Validation
            $validated = $request->validate([
                'pet_id' => 'required|exists:pets,id',
                'last_name' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'address' => 'required|string',
                'contact_number' => 'required|string|max:20',
                'dob' => 'required|date|before_or_equal:' . now()->subYears(18)->toDateString(),
                'valid_id' => 'required|file|mimes:jpeg,png,jpg,pdf|max:51200',
                'valid_id_back' => 'required|file|mimes:jpeg,png,jpg,pdf|max:51200',
                'previous_experience' => 'required|in:yes,no',
                'other_pets' => 'required|in:yes,no',
                'financial_preparedness' => 'required|in:yes,no',
            ], [
                'dob.before_or_equal' => 'You must be at least 18 years old to apply for an adoption.',
                'dob.required' => 'Date of birth is required.',
                'dob.date' => 'Please enter a valid date.',
            ]);

            // Check pet availability
            $pet = Pet::findOrFail($validated['pet_id']);

            if (!$pet->isAvailableForAdoption()) {
                return back()->with('error', 'This pet is not available for adoption.');
            }

            // Check for existing active adoption requests
            $hasActiveRequest = Adoption::where('user_id', Auth::id())
                ->where('pet_id', $validated['pet_id'])
                ->whereIn('status', ['pending', 'approved'])
                ->exists();

            if ($hasActiveRequest) {
                return back()->with('error', "You already have an active adoption request for {$pet->name}. Please wait for a response.");
            }

            // Check for rejected or archived requests
            $existingRequest = Adoption::where('user_id', Auth::id())
                ->where('pet_id', $validated['pet_id'])
                ->whereIn('status', ['rejected', 'archived'])
                ->first();

            if ($existingRequest) {
                $existingRequest->update(['status' => 'archived']);
            }

            // File upload for valid_id
            if ($request->hasFile('valid_id')) {
                $file = $request->file('valid_id');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '-' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;
                $path = $file->storeAs('adoption/valid_ids', $filename, 'public');
                $validated['valid_id'] = $path;
                Log::info('File stored at path', ['path' => $path]);
            }

            // File upload for valid_id_back
            if ($request->hasFile('valid_id_back')) {
                $fileBack = $request->file('valid_id_back');
                $extensionBack = $fileBack->getClientOriginalExtension();
                $filenameBack = time() . '-' . Str::slug(pathinfo($fileBack->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extensionBack;
                $pathBack = $fileBack->storeAs('adoption/valid_ids', $filenameBack, 'public');
                $validated['valid_id_back'] = $pathBack;
                Log::info('Valid ID Back file stored at path', ['path' => $pathBack]);
            }

            try {
                $adoptionRequest = Adoption::create([
                    'user_id' => Auth::id(),
                    'pet_id' => $validated['pet_id'],
                    'last_name' => $validated['last_name'],
                    'first_name' => $validated['first_name'],
                    'middle_name' => $validated['middle_name'],
                    'address' => $validated['address'],
                    'contact_number' => $validated['contact_number'],
                    'dob' => $validated['dob'],
                    'valid_id' => $validated['valid_id'],
                    'valid_id_back' => $validated['valid_id_back'],
                    'previous_experience' => $validated['previous_experience'],
                    'other_pets' => $validated['other_pets'],
                    'financial_preparedness' => $validated['financial_preparedness'],
                    'status' => 'pending',
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->errorInfo[1] == 1062) {
                    return back()->with('error', 'You have already submitted a request for this pet.');
                }
                throw $e;
            }

            $pet->update(['status' => Pet::STATUS_PENDING]);

            return to_route('adopt.log')->with('success', "Adoption request submitted successfully!");
        } catch (\Exception $e) {
            Log::error("Adoption Request Error: " . $e->getMessage());
            return back()->with('error', 'Failed to submit adoption request: ' . $e->getMessage());
        }
    }

    public function edit(Adoption $adoption): Response
    {
        if (Auth::id() !== $adoption->user_id || $adoption->status !== 'pending') {
            abort(403, 'Unauthorized action');
        }

        $pets = Pet::all();

        return Inertia::render('Adoptions/Edit', [
            'adoption' => $adoption,
            'pets' => $pets,
        ]);
    }

    public function update(Request $request, Adoption $adoption)
    {
        if (Auth::id() !== $adoption->user_id || $adoption->status !== 'pending') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'address' => 'required|string',
            'contact_number' => 'required|string|max:20',
            'dob' => 'required|date|before_or_equal:' . now()->subYears(18)->toDateString(),
            'valid_id' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:51200',
            'valid_id_back' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:51200',
            'previous_experience' => 'required|in:yes,no',
            'other_pets' => 'required|in:yes,no',
            'financial_preparedness' => 'required|in:yes,no',
        ], [
            'dob.before_or_equal' => 'You must be atleast 18 years old to apply for an adoption.',
            'dob.required' => 'Date of birth is required.',
            'dob.date' => 'Please enter a valid date.',
        ]);

        if ($request->hasFile('valid_id')) {
            if ($adoption->valid_id) {
                Storage::disk('public')->delete($adoption->valid_id);
            }
            $validated['valid_id'] = $request->file('valid_id')->store('adoption/valid_ids', 'public');
        }

        if ($request->hasFile('valid_id_back')) {
            if ($adoption->valid_id_back) {
                Storage::disk('public')->delete($adoption->valid_id_back);
            }
            $validated['valid_id_back'] = $request->file('valid_id_back')->store('adoption/valid_ids', 'public');
        }

        DB::transaction(function () use ($adoption, $validated) {
            if ($adoption->pet_id != $validated['pet_id']) {
                $adoption->pet->update(['status' => Pet::STATUS_AVAILABLE]);
                $newPet = Pet::findOrFail($validated['pet_id']);
                $newPet->update(['status' => Pet::STATUS_PENDING]);
            }

            $adoption->fill($validated);
            if ($adoption->isDirty()) {
                $adoption->save();
            }
        });

        return to_route('adopt.log')->with('success', "Adoption request with ID {$adoption->id} updated successfully.");
    }

    public function destroy(Adoption $adoption)
    {
        Log::info('Attempting to delete adoption request', [
            'user_id' => Auth::id(),
            'adoption_user_id' => $adoption->user_id,
            'adoption_status' => $adoption->status,
        ]);

        if (Auth::id() !== $adoption->user_id) {
            abort(403, 'Unauthorized: You are not the owner of this adoption request.');
        }

        if ($adoption->status !== 'pending') {
            return back()->with('error', 'Only pending adoption requests can be deleted.');
        }

        if (method_exists($adoption, 'forceDelete')) {
            $adoption->forceDelete();
        } else {
            $adoption->delete();
        }

        Log::info("Adoption request with ID {$adoption->id} deleted successfully", [
            'deleted_by' => Auth::id(),
            'adoption_id' => $adoption->id,
        ]);

        return back()->with('success', "Adoption request with ID {$adoption->id} deleted successfully.");
    }

    public function approve(Adoption $adoption)
    {
        $this->authorize('approve', $adoption);

        DB::transaction(function () use ($adoption) {
            $adoption->update(['status' => 'approved']);
            $adoption->pet->update(['status' => Pet::STATUS_ADOPTED]);
            Adoption::where('pet_id', $adoption->pet_id)
                ->where('id', '!=', $adoption->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);
        });

        return back()->with('success', 'Adoption approved successfully!');
    }

    public function reject(Adoption $adoption)
    {
        $this->authorize('reject', $adoption);

        DB::transaction(function () use ($adoption) {
            $adoption->update(['status' => 'rejected']);
            $hasOtherPending = Adoption::where('pet_id', $adoption->pet_id)
                ->where('status', 'pending')
                ->exists();

            if (!$hasOtherPending) {
                $adoption->pet->update(['status' => Pet::STATUS_AVAILABLE]);
            }
        });

        return back()->with('success', 'Adoption request rejected.');
    }

    public function adoptionLog(): Response
    {
        $user = Auth::user();
        $adoptions = $user->hasRole('Adopter') 
            ? Adoption::where('user_id', $user->id)->get()
            : Adoption::all();

        return Inertia::render('Adoptions/Log', [
            'adoptions' => $adoptions
        ]);
    }

    public function pending(): Response
    {
        $adoptions = Adoption::with('pet', 'user')
            ->where('status', 'pending')
            ->get();

        return Inertia::render('Adoptions/Pending', [
            'adoptions' => $adoptions
        ]);
    }
}
