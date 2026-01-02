<?php

namespace App\Http\Controllers\API;

use App\Models\IncomeSource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IncomeSourceController extends Controller
{
        public function index()
    {
        return auth()->user()->incomeSources;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'type' => 'required|in:bank,mno,cash',
            'balance' => 'numeric'
        ]);

        $data['user_id'] = auth()->id();

        return IncomeSource::create($data);
    }

}
