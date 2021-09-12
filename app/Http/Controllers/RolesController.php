<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return $this->successResponse($roles);
    }

  
}
