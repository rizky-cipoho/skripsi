<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Point;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'date' => 'required',
            'month' => 'required',
            'year' => 'required',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);


        $curent = strtotime($request->post('date')."-".$request->post('month')."-".$request->post('year'))*1000;

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'birth' => $curent,
            'point' => 0,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('message','Akun berhasil di buat');
    }
    public function guestLogin(){
        $rand = (string)rand(10000, 99999);
        $user = new User;
        $user->name = 'guest'.$rand;
        $user->email = $rand.'@mail.com';
        $user->password = Hash::make('guest'.$rand);
        $user->save();

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);

    }
}
