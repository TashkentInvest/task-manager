<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;

class FilePolicy
{
    /**
     * Determine if the given file can be viewed by the user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\File  $file
     * @return bool
     */
    public function view(User $user, File $file)
    {
        // Super Admin can view all files
        if ($user->roles[0]->name === 'Super Admin') {
            return true;
        }

        // Regular users can only view their own files
        return $file->user_id === $user->id;
    }

    /**
     * Determine if the user can view the file list.
     * Super Admin can view all files, others can view only their own.
     */
    public function viewAny(User $user)
    {
        // Super Admin can view all files
        return $user->roles[0]->name === 'Super Admin';
    }
}
