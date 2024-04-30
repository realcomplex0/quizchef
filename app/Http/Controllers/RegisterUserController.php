<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Models\Host;

class RegisterUserController extends Controller
{
    //
    public function store(Request $request) : RedirectResponse 
    {
        $host = Host::create([
            'username' => $request->username,
            'email' => $request->email,
            'passwordHash' => $request->password
        ]);

        return redirect(route('/', absolute: false));
    }
}
