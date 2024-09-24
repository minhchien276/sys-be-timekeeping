<?php

namespace App\ServicesAdmin\AuthAdmin;

class logout
{
    public function logout()
    {
        session()->forget('user');
        session()->forget('fullname');
        session()->forget('departmentId');
        session()->forget('image');

        return redirect()->route('login');
    }
}
