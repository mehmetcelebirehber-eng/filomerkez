<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->hasPermission('expenses.view'), 403);

        return view('expenses.index');
    }
}
