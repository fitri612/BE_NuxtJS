<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        $roleQuery = Role::query();

        // Get single data
        if ($id) {
            $role = $roleQuery->find($id);

            if ($role) {
                return ResponseFormatter::success($role, 'Role found 1');
            }

            return ResponseFormatter::error('Role not found', 404);
        }

        // Get multiple data
        $roles = $roleQuery->where('company_id', request()->company_id);
        // dd("TEST", $roles->get());

        if ($name) {
            $roles->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success(
            $roles->paginate($limit),
            'Role found'
        );
    }

    public function create(CreateRoleRequest $request)
    {
        try {
            // create role
            $role = Role::create([
                'name' => $request->name,
                'company_id' => $request->company_id,
            ]);

            // return response
            if (!$role) {
                throw new Exception('Error in creating role');
            }

            return ResponseFormatter::success(
                $role,
                'Role berhasil ditambahkan'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                $e->getMessage(),
                'Role gagal ditambahkan'
            );
        }
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        try {
            // Get role
            $role = Role::find($id);

            // Check if role exists
            if (!$role) {
                throw new Exception('Role not found');
            }

            // Update role
            $role->update([
                'name' => $request->name,
                'company_id' => $request->company_id,
            ]);

            return ResponseFormatter::success($role, 'Role updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            // Get role
            $role = Role::find($id);

            // TODO: Check if role is owned by user

            // Check if role exists
            if (!$role) {
                throw new Exception('Role not found');
            }

            // Delete role
            $role->delete();

            return ResponseFormatter::success($role, 'Role deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
