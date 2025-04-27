<?php

namespace App\Http\Controllers;

use App\Models\Adoption;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AdoptionController extends Controller
{
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
        $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:10',
        ]);

        Adoption::create([
            'user_id' => Auth::id(),
            'pet_id' => $request->pet_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'status' => 'pending',
        ]);

        return redirect()->route('adopt.log')->with('success', 'Adoption request submitted successfully.');
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
            abort(403, 'Unauthorized action');
        }

        $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:10',
        ]);

        $adoption->update($request->all());

        return redirect()->route('adopt.log')->with('success', 'Adoption request updated successfully.');
    }

    public function destroy(Adoption $adoption)
    {
        $user = Auth::user();

        if ($user->id !== $adoption->user_id && !$user->hasAnyRole(['Admin', 'Shelter'])) {
            abort(403, 'Unauthorized action');
        }

        $adoption->delete();

        return redirect()->back()->with('success', 'Adoption request deleted successfully.');
    }

    public function approve(Adoption $adoption)
    {
        $this->authorize('approve', $adoption);

        $adoption->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Adoption request approved.');
    }

    public function reject(Adoption $adoption)
    {
        $this->authorize('approve', $adoption);

        $adoption->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Adoption request rejected.');
    }

    public function adoptionLog(): Response
    {
        $user = Auth::user();

        $adoptions = $user->hasRole('Adopter')
            ? Adoption::where('user_id', $user->id)->get()
            : Adoption::with('pet', 'user')->get();

        return Inertia::render('Adoptions/Log', [
            'adoptions' => $adoptions,
        ]);
    }

    public function pending(): Response
    {
        $adoptions = Adoption::with('pet', 'user')
            ->where('status', 'pending')
            ->get();

        return Inertia::render('Adoptions/Pending', [
            'adoptions' => $adoptions,
        ]);
    }
}
