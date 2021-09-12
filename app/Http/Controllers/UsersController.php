<?php

namespace App\Http\Controllers;

use App\Model\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter');
        $search = $request->input('search');

        User::with('roles')
            ->when($search, function ($q) use ($search) {
                return  $q->Where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            })->when($filter, function ($q) use ($filter) {
                return $q->where('roles.name', $filter);
            })
            ->paginate(10);
    }
}
