<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        $users = User::where('is_active', true)->get();
        return view('auth.login', compact('users'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::findOrFail($request->user_id);
        Auth::login($user);

        // Redirect based on user permissions
        $redirectRoute = 'dashboard'; // Default fallback

        if ($user->hasPermission('view_requests')) {
            $redirectRoute = 'approval.index';
        } elseif ($user->hasPermission('create_schools')) {
            $redirectRoute = 'approval.create-school';
        } elseif ($user->hasPermission('propose_departments')) {
            $redirectRoute = 'approval.create-department';
        } elseif ($user->hasPermission('create_requests')) {
            $redirectRoute = 'approval.create';
        }

        return redirect()->route($redirectRoute)->with('success', "Đăng nhập thành công với vai trò: {$user->role}");
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Đăng xuất thành công');
    }
}
