<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Semak role dan redirect ke page yang betul
        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role == 'worker') {
            return redirect()->route('worker.dashboard');
        } elseif ($user->role == 'vendor') {
            return redirect()->route('vendor.dashboard');
        } else {
            // Kalau role: public
            return redirect()->route('public.menu');
        }
    }
}