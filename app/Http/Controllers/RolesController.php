<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::all();

        return view('roles.index', compact('roles'));
    }
}
