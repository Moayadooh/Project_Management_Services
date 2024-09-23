<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // List all roles
    public function index()
    {
        return response()->json(Role::all(), 200);
    }

    // Create a new role
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        $role = Role::create([
            'name' => $request->name,
        ]);

        return response()->json($role, 201);
    }

    // Update an existing role
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
        ]);

        $role->name = $request->name;
        $role->save();

        return response()->json($role, 200);
    }

    // Delete a role
    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(null, 204);
    }
}
