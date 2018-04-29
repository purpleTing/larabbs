<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use App\Transformers\NotificationsTransformer;

class NotificationsController extends Controller
{
    public function index()
    {
        $notifications = $this->user->notifications()->paginate(20);

        return $this->response->paginator($notifications, new NotificationsTransformer());
    }

    public function stats()
    {
        return $this->response->array([
            'unread_count' => $this->user()->notification_count,
        ]);
    }
}
