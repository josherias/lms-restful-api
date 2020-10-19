<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;


class ApiController extends Controller
{
    use ApiResponser; // using th custom trait created - will be used in all controllers exetending this controller

    public function __construct()
    {
        $this->middleware('auth:api'); //users auth with api guard with passport as its driver to protect all routes
    }

    protected function allowedAdminAction()
    {
        if (Gate::denies('admin-action')) {
            throw new AuthorizationException("This action is unauthorized");
        }
    }
}
