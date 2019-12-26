<?php

namespace App\Http\Controllers\User;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    // Edit user password
    public function edit()
    {
        return view('user.password');
    }

    // Update and encrypt user password
    public function update(Request $request)
    {
        // Validate $request
        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        // Update encrypted user password in the database and redirect to previous page
        $user = User::findOrFail(auth()->id());
        if (!Hash::check($request->current_password, $user->password)) {
            session()->flash('danger', "Your current password doesn't mach the password in the database");
            return back();
        }
        $user->password = Hash::make($request->password);
        $user->save();
        session()->flash('success', 'Your password has been updated');
        return back();
    }

}
