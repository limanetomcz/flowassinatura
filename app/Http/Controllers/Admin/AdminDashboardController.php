<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * =============================================================================
 * AdminDashboardController
 * =============================================================================
 * Handles all functionality related to the Admin Dashboard.
 *
 * Responsibilities:
 *   - Ensure only authenticated administrators can access the dashboard.
 *   - Render the main admin dashboard view.
 *
 * Middleware:
 *   - auth      -> Ensures the user is logged in.
 *   - is_admin  -> Ensures the user has admin privileges.
 */
class AdminDashboardController extends Controller
{
    /**
     * -------------------------------------------------------------------------
     * Constructor
     * -------------------------------------------------------------------------
     * Apply authentication and admin authorization middleware.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'is_admin']);
    }

    /**
     * -------------------------------------------------------------------------
     * Display Dashboard
     * -------------------------------------------------------------------------
     * Renders the main admin dashboard view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.dashboard');
    }
}
