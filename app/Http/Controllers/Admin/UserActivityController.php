<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserActivityLog;
use Illuminate\View\View;

class UserActivityController extends Controller {
    public function index(): View {
        $logs = UserActivityLog::with( 'user' )
            ->orderByDesc( 'created_at' )
            ->paginate( 50 );

        return view( 'admin.activity', [
            'logs' => $logs,
        ] );
    }
}
