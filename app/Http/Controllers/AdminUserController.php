<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->string('role'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $roles = ['admin', 'da', 'lgu', 'farmer'];
        $statuses = ['active', 'inactive'];

        return view('admin.users', [
            'users' => $users,
            'roles' => $roles,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Show a form for creating a new user.
     */
    public function create(): View
    {
        $roles = ['admin', 'da', 'lgu', 'farmer'];
        $statuses = ['active', 'inactive'];

        return view('admin.users-create', [
            'roles' => $roles,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:farmer,lgu,da,admin'],
            'status' => ['required', 'in:active,inactive'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $password = $validated['password'] ?? Str::random(12);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'status' => $validated['status'],
            'password' => $password,
        ]);

        return redirect()
            ->route('sys-admin.users')
            ->with('status', 'User created successfully.');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(User $user): RedirectResponse
    {
        // Don't allow resetting your own password from here to avoid lockouts
        if (auth()->id() === $user->id) {
            return redirect()
                ->route('sys-admin.users')
                ->with('status', 'Use your profile page to change your own password.');
        }

        $newPassword = Str::random(12);
        $user->update([
            'password' => $newPassword,
        ]);

        return redirect()
            ->route('sys-admin.users')
            ->with('status', 'Password reset. New password: ' . $newPassword);
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return redirect()
                ->route('sys-admin.users')
                ->with('status', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()
            ->route('sys-admin.users')
            ->with('status', 'User deleted successfully.');
    }
}
