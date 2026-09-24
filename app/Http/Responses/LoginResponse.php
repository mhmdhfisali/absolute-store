<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  Request  $request
     */
    public function toResponse($request): Response
    {
        $user = Auth::user();

        // Jika Super Admin, arahkan ke Command Center Dashboard
        if ($user && $user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        // Pengguna / Member reguler langsung diarahkan ke Etalase Pembelian Produk
        return redirect()->intended(route('home'));
    }
}
