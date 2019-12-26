<?php

namespace App\Http\Controllers\User;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    // Edit user profile
    public function edit()
    {
        return view('user.profile');
    }

    // Update user profile
    public function update(Request $request)
    {
        // Validate $request
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . auth()->id()
        ]);

        // Update user in the database and redirect to previous page
        $user = User::findOrFail(auth()->id());
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        session()->flash('success', 'Your profile has been updated');
        return back();
    }
}
