<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\linkedinNotify;

class NotificationController extends Controller
{
    public function index()
    {
        $linkedinNotify = linkedinNotify::select('not_id','fullname')->WhereNotNull('fullname')->leftjoin('users', 'linkedin_notify.not_user', '=', 'users.id')
        ->get();

        return response()->json([
            'data' => $linkedinNotify,
            'message' => 'Success'
        ], 200);
    }
}
