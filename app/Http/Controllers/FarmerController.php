<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Lgu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FarmerController extends Controller
{
    /**
     * Display a listing of the farmers and the create form.
     */
    public function index(Request $request): View
    {
        $query = Farmer::with('lgu');

        if ($request->filled('municipality')) {
            $query->where('municipality', $request->string('municipality'));
        }

        if ($request->filled('lgu_id')) {
            $query->where('lgu_id', $request->integer('lgu_id'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('rsbsa_number', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $farmers = $query
            ->orderBy('municipality')
            ->orderBy('barangay')
            ->orderBy('last_name')
            ->paginate(10)
            ->withQueryString();

        $lgus = Lgu::orderBy('municipality')->orderBy('lgu_name')->get();

        $municipalities = [
            'Baco',
            'Bansud',
            'Bongabong',
            'Bulalacao',
            'Calapan City',
            'Gloria',
            'Mansalay',
            'Naujan',
            'Pinamalayan',
            'Pola',
            'Puerto Galera',
            'Roxas',
            'San Teodoro',
            'Socorro',
            'Victoria',
        ];

        return view('admin.farmers', [
            'farmers' => $farmers,
            'lgus' => $lgus,
            'municipalities' => $municipalities,
        ]);
    }

    /**
     * Store a newly created farmer in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'lgu_id'         => ['required', 'exists:lgus,id'],
            'rsbsa_number'   => [
                'required',
                'string',
                'max:255',
                'regex:/^\d{2}-\d{2}-\d{2}-\d{3}-\d{6}$/',
                'unique:farmers,rsbsa_number',
            ],
            'first_name'     => ['required', 'string', 'max:255'],
            'last_name'      => ['required', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'email'          => ['nullable', 'email', 'max:255'],
            'crop_type'      => ['nullable', 'string', 'max:255'],
            'farm_location'  => ['nullable', 'string'],
            'barangay'       => ['required', 'string', 'max:255'],
            'municipality'   => ['required', 'string', 'max:255'],
            'province'       => ['required', 'string', 'max:255'],
            'latitude'       => ['nullable', 'numeric'],
            'longitude'      => ['nullable', 'numeric'],
        ]);

        Farmer::create($validated);

        return redirect()
            ->route('sys-admin.farmers')
            ->with('status', 'Farmer registered successfully.');
    }

    /**
     * Update an existing farmer.
     */
    public function update(Request $request, Farmer $farmer): RedirectResponse
    {
        $validated = $request->validate([
            'lgu_id'         => ['required', 'exists:lgus,id'],
            'rsbsa_number'   => [
                'required',
                'string',
                'max:255',
                'regex:/^\d{2}-\d{2}-\d{2}-\d{3}-\d{6}$/',
                Rule::unique('farmers', 'rsbsa_number')->ignore($farmer->id),
            ],
            'first_name'     => ['required', 'string', 'max:255'],
            'last_name'      => ['required', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'email'          => ['nullable', 'email', 'max:255'],
            'crop_type'      => ['nullable', 'string', 'max:255'],
            'farm_location'  => ['nullable', 'string'],
            'barangay'       => ['required', 'string', 'max:255'],
            'municipality'   => ['required', 'string', 'max:255'],
            'province'       => ['required', 'string', 'max:255'],
            'latitude'       => ['nullable', 'numeric'],
            'longitude'      => ['nullable', 'numeric'],
        ]);

        $farmer->update($validated);

        return redirect()
            ->route('sys-admin.farmers')
            ->with('status', 'Farmer updated successfully.');
    }

    /**
     * Bulk delete farmers.
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);

        if (! empty($ids)) {
            Farmer::whereIn('id', $ids)->delete();
            $message = 'Selected farmers deleted successfully.';
        } else {
            $message = 'No farmers selected.';
        }

        return redirect()
            ->route('sys-admin.farmers')
            ->with('status', $message);
    }
}
