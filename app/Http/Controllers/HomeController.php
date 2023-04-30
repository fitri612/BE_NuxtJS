<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Employee;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $employees = Employee::all();
            return ResponseFormatter::success($employees, 'Data retrieved successfully');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 'Data retrieval failed');
        }
    }
}
