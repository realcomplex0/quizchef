<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules;
use App\Models\Host;

class RegisterUserController extends Controller
{
    //
    public function store(Request $request) : RedirectResponse 
    {
        $request->validate([
            'username' => 'required|string|max:20',
            'email' => 'required|string|lowercase|email|max:255|unique:'.Host::class,
            'password' => 'required|string|same:password_repeat'
        ]);
        $host = Host::create([
            'username' => $request->username,
            'email' => $request->email,
            'passwordHash' => $request->password
        ]);

        return redirect(route('/', absolute: false));
    }
}
