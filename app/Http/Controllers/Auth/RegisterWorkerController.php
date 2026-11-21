<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterWorkerController extends Controller
{
    // Papar borang register worker
    public function create()
    {
        return view('auth.register_worker');
    }

    // Simpan akaun worker baru (Status: Pending)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'accepted',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'worker',       // Terus daftar sebagai worker
            'status' => 'pending',    // Tapi status pending, kena tunggu admin approve
        ]);

        return redirect()->route('login')->with('status', 'Worker registered successfully! Please wait for Admin to approve your account.');
    }
}