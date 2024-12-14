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
        'password' => 'required|string|min:8|confirmed',
        'role' => 'nullable|string|in:admin,user,super-admin',
    ];

    // FOR CRUD OPERATIONS (NOT AUTHENTICATION)

    public function store(Request $request) {
        $validated = $request->validate($this->rules);
        $validated['password'] = bcrypt($validated['password']);
        $validated['role'] = $validated['role'] ?? $this->defaultRole;
        $user = User::create($validated);
        $user->assignRole($validated['role']);
        return new $this->resource($user);
    }

    public function update(Request $request, $id) {
        $rules = [
            'username' => 'required|string|min:6|max:255|unique:users,username,'.$id,
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'nullable|string|in:admin,user,super-admin',
        ];
        
        $user = User::findOrFail($id);

        $validated = $request->validate(\Arr::except($rules, ['password', 'password_confirmation']));

        if ($request->filled('password')) {
            $confirmed = $request->validate([
                'password' => 'required|string|min:8', 
                'password_confirmation' => 'required|same:password'
            ]);
            $validated['password'] = \Hash::make($request->input('password'));
        }

        $user->update($validated);
        $user->roles()->detach();
        $user->assignRole($request->input('role') ?? $this->defaultRole);
        return new $this->resource($user);
    }

    public function destroy($id) {
        return response()->json(['message' => 'Method not implemented'], 501);  

        // $user = User::find($id);
        // if (!$user) {
        //     return response()->json(['message' => 'User not found'], 404);
        // }
        // $requestingUser = auth()->user();
        // $userRoles = $user->getRoleNames();
        // if ($requestingUser->id == $user->id) {
        //     return response()->json(['message' => 'You cannot delete yourself'], 422);
        // }
        // if (in_array('super-admin', $userRoles)) {
        //     return response()->json(['message' => 'You cannot delete a super-admin'], 422);
        // }
        // if ($user->delete()) {
        //     return response()->json(['message' => 'User deleted successfully'], 200);
        // }
        
    }

}
