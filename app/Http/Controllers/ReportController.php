<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display a listing of the reports.
     */
    public function index(Request $request): View
    {
        $reports = Report::with(['farmer', 'lgu'])
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.reports', [
            'reports' => $reports,
        ]);
    }
}
