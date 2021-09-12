<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Http\Requests\SignupRequest;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function Login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            abort_if(!$user, 404);
            $access = $user->createToken('authToken')->accessToken;
            $user['accessToken'] = $access ;
        } else {
            return $this->notFoundResponse('Invalid Enterd Credentials');
        }
        return $this->successResponse($user);
    }

    public function CreateAdmin(AdminRequest $request)
    {
        $user = User::create($request->validated());
        $user->assignRole($request->type);

        return $this->successResponse($user);
    }

    public function Signup(SignupRequest $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();
        $user->assignRole('guest');

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            $user = User::where('email',$request->email)->first();
            $access = $user->createToken('authToken')->accessToken;
            $user['accessToken'] = $access ;
        }

        return $this->successResponse($user);
    }

    public function index(Request $request)
    {
        $filter = $request->input('filter');
        $search = $request->input('search');

        $posts = Admin::with('roles')->when($filter, function ($q) use ($filter) {
                    return $q->where('roles.name', $filter);
                    })->
                    when($search , function ($q) use ($search) {
                    return $q->Where('name', 'LIKE', "%{$search}%");
                })->paginate(10);

        return $this->successResponse($posts);
    }

}
