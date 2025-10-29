<?php

namespace App\Listeners;

use App\Models\Group;
use Illuminate\Auth\Events\Registered;

class AssignRoleByBranch
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        // Only proceed if user has a branch selected
        if (!$user->branch_id) {
            return;
        }

        // Ensure the user is associated with a valid group in this branch; default to 'A' if not set
        if (!$user->group_id) {
            $defaultGroup = Group::where('branch_id', $user->branch_id)
                ->where('name', 'A')
                ->first();

            if ($defaultGroup) {
                $user->group_id = $defaultGroup->id;
                $user->save();
            }
        }

        // Assign a baseline role based on branch context. Customize mapping as needed.
        // For now, assign the generic 'user' role if no role assigned yet.
        if (method_exists($user, 'hasRole') && method_exists($user, 'assignRole')) {
            try {
                if ($user->getRoleNames()->isEmpty()) {
                    $user->assignRole('user');
                }
            } catch (\Throwable $e) {
                // Silently ignore if roles are not seeded yet
            }
        }
    }
}
