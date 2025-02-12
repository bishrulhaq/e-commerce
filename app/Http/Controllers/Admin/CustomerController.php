<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Roles;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class CustomerController extends Controller
{
    /**
     * Search customers by name and return their IDs.
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('query');

        $customers = User::where('name', 'LIKE', "%{$query}%")
            ->select('id', 'name')
            ->where('role_id', Roles::CUSTOMER)
            ->limit(10)
            ->get();

        return response()->json($customers);
    }
    /**
     * Display a listing of customers.
     */
    public function index(): InertiaResponse
    {
        $customers = User::where('role_id', 2)->paginate(10); // Assuming role_id 2 is for customers
        return Inertia::render('Admin/Customers/Index', ['customers' => $customers]);
    }

    /**
     * Show form for creating a new customer.
     */
    public function create(): InertiaResponse
    {
        return Inertia::render('Admin/Customers/Create');
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
        ]);

        $password = Str::random(10);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role_id' => Roles::CUSTOMER,
        ]);

        // Send email with login credentials
//        Mail::to($user->email)->send(new \App\Mail\NewCustomerMail($user, $password));

        return redirect()->route('admin.customers.index')->with('success', 'Customer created successfully!');
    }

    /**
     * Reset user password and send new credentials via email.
     */
    public function resetPassword(User $user): RedirectResponse
    {
        $newPassword = Str::random(10);
        $user->update(['password' => Hash::make($newPassword)]);

        // Send email with new password
        Mail::to($user->email)->send(new \App\Mail\ResetPasswordMail($user, $newPassword));

        return back()->with('success', 'Password reset successfully!');
    }

    /**
     * Update customer details.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return back()->with('success', 'Customer updated successfully!');
    }
}
