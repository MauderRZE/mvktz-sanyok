<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use App\Models\AuthLog;
use Illuminate\Http\Request;

class AuthLogSubscriber
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handleUserLogin($event)
    {
        AuthLog::create([
            'user_id' => $event->user->id,
            'event' => 'login',
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
        ]);
    }

    public function handleUserLogout($event)
    {
        AuthLog::create([
            'user_id' => $event->user ? $event->user->id : null,
            'event' => 'logout',
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
        ]);
    }

    public function handleUserFailed($event)
    {
        AuthLog::create([
            'user_id' => $event->user ? $event->user->id : null,
            'event' => 'failed',
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
        ]);
    }

    public function subscribe($events)
    {
        $events->listen(
            Login::class,
            [AuthLogSubscriber::class, 'handleUserLogin']
        );

        $events->listen(
            Logout::class,
            [AuthLogSubscriber::class, 'handleUserLogout']
        );

        $events->listen(
            Failed::class,
            [AuthLogSubscriber::class, 'handleUserFailed']
        );
    }
}
