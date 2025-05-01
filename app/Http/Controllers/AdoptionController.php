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
        $pets = Pet::where('status', Pet::STATUS_AVAILABLE)->get();
        $adoptions = Adoption::with('user', 'pet')->get();

        return Inertia::render('Adoptions/Index', [
            'pets' => $pets,
            'adoptions' => $adoptions
        ]);
    }

    public function create($pet = null): Response
    {
        $user = Auth::user();

        // Get all available and pending pets
        $pets = Pet::whereIn('status', [Pet::STATUS_AVAILABLE, Pet::STATUS_PENDING])->get();

        $selectedPet = $pet ? Pet::find($pet) : null;

        return Inertia::render('Adoptions/Create', [
            'selectedPet' => $selectedPet,
            'pets' => $pets,
        ]);
    }

    public function store(Request $request)
    {
        try {
            Log::info('Adoption request data:', $request->all());

            $validated = $request->validate([
                'pet_id' => 'required|exists:pets,id',
                'last_name' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'address' => 'required|string|max:255',
                'contact_number' => 'required|string|max:20',
                'dob' => 'required|date|before:-18 years',
                'valid_id' => 'required|file|mimes:jpeg,png,jpg,pdf|max:51200',
                'valid_id_back' => 'required|file|mimes:jpeg,png,jpg,pdf|max:51200',
                'previous_experience' => 'required|in:yes,no',
                'other_pets' => 'required|in:yes,no',
                'financial_preparedness' => 'required|in:yes,no',
            ], [
                'dob.before' => 'You must be at least 18 years old to adopt a pet.',
                'previous_experience.in' => 'Please select whether you have previous pet ownership experience.',
                'other_pets.in' => 'Please select whether you have other pets at home.',
                'financial_preparedness.in' => 'Please select whether you are financially prepared for pet care.',
            ]);

            $pet = Pet::findOrFail($validated['pet_id']);

            if ($pet->status !== Pet::STATUS_AVAILABLE) {
                return back()->with('error', 'This pet is no longer available for adoption.');
            }

            // Check for existing active adoption request
            $existingRequest = Adoption::where('user_id', Auth::id())
                ->where('pet_id', $pet->id)
                ->whereIn('status', [Adoption::STATUS_PENDING, Adoption::STATUS_APPROVED])
                ->first();

            if ($existingRequest) {
                $message = match ($existingRequest->status) {
                    Adoption::STATUS_PENDING => 'You already have a pending adoption request for this pet.',
                    Adoption::STATUS_APPROVED => 'You have already adopted this pet.',
                    default => 'You already have an active adoption request for this pet.'
                };
                return back()->with('error', $message);
            }

            // Handle file uploads
            $validIdPath = $request->file('valid_id')->store('adoption/valid_ids', 'public');
            $validIdBackPath = $request->file('valid_id_back')->store('adoption/valid_ids', 'public');

            Log::info('Files stored at:', [
                'valid_id' => $validIdPath,
                'valid_id_back' => $validIdBackPath
            ]);

            $adoption = Adoption::create([
                'user_id' => Auth::id(),
                'pet_id' => $pet->id,
                'last_name' => $validated['last_name'],
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'address' => $validated['address'],
                'contact_number' => $validated['contact_number'],
                'dob' => $validated['dob'],
                'valid_id' => $validIdPath,
                'valid_id_back' => $validIdBackPath,
                'previous_experience' => $validated['previous_experience'],
                'other_pets' => $validated['other_pets'],
                'financial_preparedness' => $validated['financial_preparedness'],
                'status' => Adoption::STATUS_PENDING,
            ]);

            // Update pet status
            $pet->update(['status' => Pet::STATUS_PENDING]);

            return redirect()->route('adopt.log')
                ->with('success', 'Your adoption request has been submitted successfully! We will review your application and get back to you soon.');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error during adoption request:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return back()->with('error', 'You already have an active adoption request for this pet.');
            }

            return back()->with('error', 'An error occurred while processing your request. Please try again later.');
        } catch (\Exception $e) {
            Log::error('Error during adoption request:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'An unexpected error occurred. Please try again later.');
        }
    }

    public function edit(Adoption $adoption): Response
    {
        if (Auth::id() !== $adoption->user_id || $adoption->status !== Adoption::STATUS_PENDING) {
            abort(403, 'Unauthorized action');
        }

        $pets = Pet::all();
        $adoption->load('pet');  // Eager load the pet relationship

        return Inertia::render('Adoptions/Edit', [
            'adoption' => $adoption,
            'pets' => $pets,
        ]);
    }

    public function update(Request $request, Adoption $adoption)
    {
    
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'dob' => 'required|date',
            'previous_experience' => 'required|in:yes,no',
            'other_pets' => 'required|in:yes,no',
            'financial_preparedness' => 'required|in:yes,no',
            'valid_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:51200',
            'valid_id_back' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:51200',
        ]);
    
        // Handle file uploads
        if ($request->hasFile('valid_id')) {
            $validated['valid_id'] = $request->file('valid_id')->store('valid_ids', 'public');
        }
    
        if ($request->hasFile('valid_id_back')) {
            $validated['valid_id_back'] = $request->file('valid_id_back')->store('valid_ids', 'public');
        }
    
        $adoption->update($validated);
        
        return redirect()->route('adopt.log')->with('success', "Adoption request with ID {$adoption->id} updated successfully.");
    }
    

    public function destroy(Adoption $adoption)
    {
        $this->authorize('delete', $adoption);

        if ($adoption->status !== 'pending') {
            return back()->with('error', 'Only pending adoption requests can be deleted.');
        }

        // Load the pet relationship
        $adoption->load('pet');
        
        // Log current pet status and adoption details
        Log::info('Before deletion:', [
            'adoption_id' => $adoption->id,
            'pet_id' => $adoption->pet_id,
            'pet_status' => $adoption->pet->status,
            'adoption_status' => $adoption->status
        ]);
        
        // Update pet status back to available
        $adoption->pet->update(['status' => 'available']);
        
        // Log updated pet status
        Log::info('After pet status update:', [
            'pet_id' => $adoption->pet_id,
            'new_status' => $adoption->pet->fresh()->status
        ]);
        
        $adoption->delete();

        return redirect()->route('adopt.log')->with('success', 'Adoption request deleted successfully.');
    }

    public function cancel(Adoption $adoption)
    {
        $this->authorize('update', $adoption);

        if ($adoption->status !== 'pending') {
            return back()->with('error', 'Only pending adoption requests can be cancelled.');
        }

        $adoption->update(['status' => 'cancelled']);

        return redirect()->route('adopt.log')->with('success', 'Adoption request cancelled successfully.');
    }

    public function approve(Adoption $adoption)
    {
        $adoption->update(['status' => Adoption::STATUS_APPROVED]);
        $adoption->pet->update(['status' => Pet::STATUS_ADOPTED]);
        return redirect()->back()->with('success', 'Adoption request approved successfully.');
    }

    public function reject(Adoption $adoption)
    {

        $adoption->load('pet');

        $adoption->update(['status' => Adoption::STATUS_REJECTED]);
        $adoption->pet->update(['status' => Pet::STATUS_AVAILABLE]);
        return redirect()->back()->with('success', 'Adoption request rejected successfully.');
    }

    public function adoptionLog(): Response
    {
        $adoptions = Adoption::with('pet')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $user = Auth::user();
        $roles = $user->roles->pluck('name')->toArray();

        return Inertia::render('Adoptions/Log', [
            'adoptions' => $adoptions,
            'auth' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles
                ]
            ]
        ]);
    }

    public function pending(): Response
    {
        $adoptions = Adoption::with('user', 'pet')
            ->where('status', Adoption::STATUS_PENDING)
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Adoptions/Pending', [
            'adoptions' => $adoptions
        ]);
    }
}