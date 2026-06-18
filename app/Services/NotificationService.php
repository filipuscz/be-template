<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationService
{
    /**
     * Get paginated notifications for a user.
     */
    public function index(User $user, int $limit = 15): LengthAwarePaginator
    {
        return $user->notifications()->paginate($limit);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(User $user, string $id): void
    {
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(User $user): void
    {
        $user->unreadNotifications->markAsRead();
    }

    /**
     * Delete a specific notification.
     */
    public function delete(User $user, string $id): void
    {
        $notification = $user->notifications()->findOrFail($id);
        $notification->delete();
    }
}
