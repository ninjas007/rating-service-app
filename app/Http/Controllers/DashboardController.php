<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public $page = 'dashboard';
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        return view('dashboard.index', [
            'page' => $this->page,
            'title' => 'Dashboard'
        ]);
    }
}
