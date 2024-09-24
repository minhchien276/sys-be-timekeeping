<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\loginRequest;
use App\ServicesAdmin\AuthAdmin\login;
use App\ServicesAdmin\AuthAdmin\logout;

class AuthController extends Controller
{
    private $login;
    private $logout;

    public function __construct(
        login $login,
        logout $logout,
    ) {
        $this->login = $login;
        $this->logout = $logout;
    }

    public function login()
    {
        return $this->login->login();
    }

    public function signIn(loginRequest $request)
    {
        return $this->login->signIn($request);
    }

    public function logout()
    {
        return $this->logout->logout();
    }
}
