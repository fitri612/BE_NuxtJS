<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateResponsibilityRequest;
use App\Models\Responsibility;
use Exception;
use Illuminate\Http\Request;

class ResponsibilityController extends Controller
{

    public function fetch(Request $request)
    {
        // dd($request->all());
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        $responsibilityQuery = Responsibility::query();
        // dd($responsibilityQuery);

        // Get single data
        if ($id) {
            $responsibility = $responsibilityQuery->find($id);

            if ($responsibility) {
                return ResponseFormatter::success($responsibility, 'Responsibility found');
            }

            return ResponseFormatter::error('Responsibility not found', 404);
        }

        // Get multiple data
        $responsibilities = $responsibilityQuery->where('role_id', $request->role_id);

        if ($name) {
            // dd($name);
            $responsibilities->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success(
            $responsibilities->paginate($limit),
            'Responsibilities found'
        );
    }


    public function create(CreateResponsibilityRequest $request)
    {
        // dd($request->all());
        try {
            // create responsibility
            $responsibility = Responsibility::create([
                'name' => $request->name,
                'role_id' => $request->role_id,
            ]);

            // dd($responsibility);

            // return response
            if (!$responsibility) {
                throw new Exception('Error in creating responsibility');
            }

            return ResponseFormatter::success(
                $responsibility,
                'Responsibility berhasil ditambahkan'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                $e->getMessage(),
                'Responsibility gagal ditambahkan'
            );
        }
    }

    public function delete($id)
    {
        try {
            // Get responsibility
            $responsibility = Responsibility::find($id);

            // TODO: Check if responsibility is owned by user

            // Check if responsibility exists
            if (!$responsibility) {
                throw new Exception('Responsibility not found');
            }

            // Delete responsibility
            $responsibility->delete();

            return ResponseFormatter::success($responsibility, 'Responsibility deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
