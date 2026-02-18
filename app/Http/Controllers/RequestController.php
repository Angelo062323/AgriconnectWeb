<?php

namespace App\Http\Controllers;

use App\Models\AssistanceRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RequestController extends Controller
{
    /**
     * Display a listing of the assistance requests.
     */
    public function index(Request $request): View
    {
        $requests = AssistanceRequest::with(['farmer', 'lgu'])
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.requests', [
            'requests' => $requests,
        ]);
    }
}
