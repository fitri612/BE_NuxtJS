<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{

    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 5);

        if ($id) {
            $company = Company::find($id);

            if ($company) {
                return ResponseFormatter::success(
                    $company,
                    'Data company berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    'Data company tidak ada',
                    404
                );
            }
        }

        $companies = Company::with(['users']);
        if ($name) {
            $companies->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success(
            $companies->paginate($limit),
            'Companiest list data berhasil diambil'
        );
    }

    public function create(CreateCompanyRequest $request)
    {
        try {
            // check logo
            if ($request->hasFile('logo')) {
                $file = $request->file('logo')->store('public/logos');
            }

            // create company
            $company = Company::create([
                'name' => $request->name,
                'logo' => $file,
            ]);

            // return response
            if (!$company) {
                throw new Exception('Error in creating company');
            }

            // attach user to company
            $user = User::find(Auth::user()->id);
            $user->companies()->attach($company->id);

            // load company with user
            $company->load('users');

            return ResponseFormatter::success(
                $company,
                'Company berhasil ditambahkan'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                $e->getMessage(),
                'Company gagal ditambahkan'
            );
        }
    }

    public function update(UpdateCompanyRequest $request, $id)
    {
        // dd($request->all());
        // dd($id);
        try {
            // Get company
            $company = Company::find($id);

            // Check if company exists
            if (!$company) {
                throw new Exception('Company not found');
            }

            // Upload logo
            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('public/logos');
            }

            // Update company
            $company->update([
                'name' => $request->name,
                'logo' => $path,
            ]);

            return ResponseFormatter::success($company, 'Company updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
