<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    
    protected $model = User::class;
    protected $resource = UserResource::class;
    protected $defaultRole = 'user';
    protected $rules = [
        'username' => 'required|string|min:6|max:255|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        'password_confirmation' => 'required|same:password',
        'role' => 'nullable|string|in:admin,user,super-admin',
    ];

    // FOR CRUD OPERATIONS (NOT AUTHENTICATION)

    public function store(Request $request) {
        return response()->json(['message' => 'Method not implemented'], 501);  
    }

    public function update(Request $request, $id) {
        return response()->json(['message' => 'Method not implemented'], 501);  
    }

}
