<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Jobs\BatchCreateUsers;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('SuperAdmin')) {
            $users = User::with('roles')->get();
        } elseif ($user->hasRole('Admin')) {
            $users = User::with('roles')->whereHas('roles', function ($query) {
                $query->where('name', 'User');
            })->get();
        } elseif ($user->hasRole('User')) {
            $users = collect([$user->load('roles')]);
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // return response()->json([
        //     'success' => true,
        //     'data' => $users
        // ]);
        return view('users.index', compact('users'));
    }


    public function create()
    {
        $roles = Role::all();
        $authUser = Auth::user();

        if ($authUser->hasRole('SuperAdmin')) {
            $roles = Role::whereIn('name', ['Admin', 'User'])->get();
        } else {
            abort(403, 'Unauthorized');
        }

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $authUser = Auth::user();

        if ($authUser->hasRole('SuperAdmin')) {
            $roles = Role::whereIn('name', ['Admin', 'User'])->get();
        } else {
            $roles = collect();
        }

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // Only SuperAdmin can update roles
        if (auth()->user()->hasRole('SuperAdmin') && $request->filled('role')) {
            $user->syncRoles([$request->role]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('dashboard.superadmin')->with('success', 'User deleted successfully.');
    }


    public function bulkStore(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        $file = fopen($request->file('csv_file'), 'r');
        $batch = [];

        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            // Assuming: name,email,password
            if (count($data) !== 3) continue;

            $batch[] = [
                'name' => $data[0],
                'email' => $data[1],
                'password' => $data[2],
            ];
        }
        fclose($file);

        BatchCreateUsers::dispatch($batch);

        return back()->with('success', 'User batch is queued for creation!');
    }


    public function admin_edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.admin_edit', compact('user'));
    }

    public function admin_update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('dashboard.admin')->with('success', 'User updated successfully!');
    }

    public function user_destroy(User $user)
    {
        $user->delete(); // Soft delete
        return redirect()->route('dashboard.admin')->with('success', 'User soft-deleted successfully.');
    }
}
