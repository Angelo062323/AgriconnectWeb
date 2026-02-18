<?php

namespace App\Http\Controllers;

use App\Models\Lgu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LguController extends Controller
{
    /**
     * Display a listing of the LGUs and the create form.
     */
    public function index(Request $request): View
    {
        $query = Lgu::query();

        if ($request->filled('municipality')) {
            $query->where('municipality', $request->string('municipality'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where('lgu_name', 'like', "%{$search}%");
        }

        $lgus = $query
            ->orderBy('municipality')
            ->orderBy('lgu_name')
            ->paginate(10)
            ->withQueryString();

        // Example municipalities for quick selection
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

        return view('admin.lgu', [
            'lgus' => $lgus,
            'municipalities' => $municipalities,
        ]);
    }

    /**
     * Store a newly created LGU in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'lgu_name'      => ['required', 'string', 'max:255'],
            'municipality'  => ['required', 'string', 'max:255'],
            'province'      => ['required', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
        ]);

        Lgu::create($validated);

        return redirect()
            ->route('sys-admin.lgu')
            ->with('status', 'LGU created successfully.');
    }

    /**
     * Update an existing LGU.
     */
    public function update(Request $request, Lgu $lgu): RedirectResponse
    {
        $validated = $request->validate([
            'lgu_name'      => ['required', 'string', 'max:255'],
            'municipality'  => ['required', 'string', 'max:255'],
            'province'      => ['required', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
        ]);

        $lgu->update($validated);

        return redirect()
            ->route('sys-admin.lgu')
            ->with('status', 'LGU updated successfully.');
    }

    /**
     * Bulk delete LGUs.
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);

        if (! empty($ids)) {
            Lgu::whereIn('id', $ids)->delete();
            $message = 'Selected LGUs deleted successfully.';
        } else {
            $message = 'No LGUs selected.';
        }

        return redirect()
            ->route('sys-admin.lgu')
            ->with('status', $message);
    }
}
