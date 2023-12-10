<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        return view('auth.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();

        return view('auth.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $userData = $request->validate([
            'name' => 'required|max:60',
            'email' => 'required|max:255|unique:users,email,'.auth()->id(),
            'telegram_chat_id' => 'nullable|string|max:255',
        ]);

        $request->user()->update($userData);

        return redirect()->route('profile.show');
    }
}
