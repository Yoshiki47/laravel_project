<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * 会社一覧を表示する
     * 
     * @return view
     */
    public function showCompanyList()
    {
        $companies = Company::all();

        return view('company', ['companies' => $companies]);
    }
}
