<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Models\Team;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{

    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        $teamQuery = Team::query();

        // Get single data
        if ($id) {
            $team = $teamQuery->find($id);

            if ($team) {
                return ResponseFormatter::success($team, 'Team found');
            }

            return ResponseFormatter::error('Team not found', 404);
        }

        // Get multiple data
        $teams = $teamQuery->where('company_id', request()->company_id);

        if ($name) {
            $teams->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success(
            $teams->paginate($limit),
            'Companies found'
        );
    }

    public function create(CreateTeamRequest $request)
    {
        try {
            // check logo
            if ($request->hasFile('icon')) {
                $file = $request->file('icon')->store('public/icons');
            }

            // create team
            $team = Team::create([
                'name' => $request->name,
                'icon' => isset($file) ? $file : null, // if file exists, set file, else set null (default
                'company_id' => $request->company_id,
            ]);

            // return response
            if (!$team) {
                throw new Exception('Error in creating team');
            }

            return ResponseFormatter::success(
                $team,
                'Team berhasil ditambahkan'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                $e->getMessage(),
                'Team gagal ditambahkan'
            );
        }
    }

    public function update(UpdateTeamRequest $request, $id)
    {
        try {
            // Get team
            $team = Team::find($id);

            // Check if team exists
            if (!$team) {
                throw new Exception('Team not found');
            }

            // Upload icon
            if($request->hasFile('icon')){
                $file = $request->file('icon')->store('public/icons');
            }

            // Update team
            $team->update([
                'name' => $request->name,
                'icon' => isset($file) ? $file : $team->icon,
                'company_id' => $request->company_id,
            ]);

            return ResponseFormatter::success($team, 'Team updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            // Get team
            $team = Team::find($id);

            // TODO: Check if team is owned by user

            // Check if team exists
            if (!$team) {
                throw new Exception('Team not found');
            }

            // Delete team
            $team->delete();

            return ResponseFormatter::success($team, 'Team deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
