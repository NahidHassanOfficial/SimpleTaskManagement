<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function summary()
    {
        $totalTasks = Task::count();
        $statusSummary = Task::summary('status');
        $severitySummary = Task::summary('severity');

        return Inertia::render('Dashboard', [
            'totalTasks' => $totalTasks,
            'statusSummary' => $statusSummary,
            'severitySummary' => $severitySummary,
        ]);
    }
}
