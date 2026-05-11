<?php

namespace App\Http\Controllers;

use App\Models\Product;


use App\Models\Company;

class CompanyController extends Controller
{
    //


    public function index()
    {
        $companies = Company::all();

        return view('frontend.company.index', compact('companies'));
    }


    public function show($slug)
    {
        $company = Company::where('slug', $slug)->firstOrFail();

        $categories = $company->categories()->get();

        foreach ($categories as $category) {
            $category->products = $category->products()
                ->latest()
                ->paginate(6);
        }

        return view('frontend.company.show', compact('company', 'categories'));
    }
}
