<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('admin.dashboard', compact('user'));
    }


}
