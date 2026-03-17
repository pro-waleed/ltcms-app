<?php

namespace App\Http\Controllers;

use App\Models\Employee;

class TrainingHistoryController extends Controller
{
    public function index(Employee $employee)
    {
        $employee->load([
            'trainingHistory' => function ($query) {
                $query->with('opportunity')->orderByDesc('completion_date')->orderByDesc('id');
            },
            'nominations' => function ($query) {
                $query->with('opportunity')->orderByDesc('nomination_date')->orderByDesc('id');
            },
        ]);

        return view('employees.history', compact('employee'));
    }
}



