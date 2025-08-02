<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function superadminDashboard()
    {
        $totalUsers = User::count();
        $totalAdmins = User::role('Admin')->count();
        $totalRegularUsers = User::role('User')->count();
        $users = User::with('roles')->get();

        return view('dashboard.superadmin', compact('totalUsers', 'totalAdmins', 'totalRegularUsers', 'users'));
    }


    public function adminDashboard()
    {
        $users = User::with('roles')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'User');
            })
            ->whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['Admin', 'SuperAdmin']);
            })
            ->get();

        // Stats
        $totalUsers = $users->count();
        $totalAdmins = User::role('Admin')->count();
        $totalRegularUsers = User::role('User')->count();

        return view('dashboard.admin', compact(
            'totalUsers',
            'totalAdmins',
            'totalRegularUsers',
            'users'
        ));
    }


    public function userDashboard()
    {
        $user = auth()->user(); // Get currently logged-in user
        return view('dashboard.user', compact('user'));
    }
}
