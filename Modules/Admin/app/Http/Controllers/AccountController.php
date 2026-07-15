<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Common\Entities\User;

class AccountController extends Controller
{
    protected function currentUser(): User
    {
        return User::findOrFail(session('admin_id'));
    }

    public function edit()
    {
        $user = $this->currentUser();
        return view('admin::account.edit', compact('user'));
    }

    public function showChangePassword()
    {
        return view('admin::account.change-password');
    }

    public function updatePassword(Request $request)
    {
        $user = $this->currentUser();

        $validated = $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('admin.account.change-password')
            ->with('success', 'Password updated successfully.');
    }

    public function showChangeEmail()
    {
        $user = $this->currentUser();
        return view('admin::account.change-email', compact('user'));
    }

    public function updateEmail(Request $request)
    {
        $user = $this->currentUser();

        $validated = $request->validate([
            'current_password' => 'required',
            'email'            => 'required|email|unique:users,email,' . $user->id,
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->update([
            'email' => $validated['email'],
        ]);

        session(['admin_email' => $validated['email']]);

        return redirect()
            ->route('admin.account.change-email')
            ->with('success', 'Email updated successfully.');
    }
    public function update(Request $request)
    {
        $user = $this->currentUser();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user->update($validated);

        session(['admin_name' => $validated['name']]);

        return redirect()
            ->route('admin.account.edit')
            ->with('success', 'Profile updated successfully.');
    }
}
