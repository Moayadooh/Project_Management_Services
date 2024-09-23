<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Get all users
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    // Get all users
    public function getMessages()
    {
        return response()->json(Notification::all(), 200);
    }

    // Assign role to user
    public function assignRole(Request $request, $userId)
    {
        $user = User::find($userId);
        $role = Role::where('name', $request->role)->first();

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $user->roles()->attach($role->id);

        return response()->json(['message' => 'Role assigned successfully']);
    }
}
