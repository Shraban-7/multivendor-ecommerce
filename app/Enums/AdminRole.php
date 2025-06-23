<?php

namespace App\Enums;

enum AdminRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case MODERATOR = 'moderator';

    public function title()
    {
        return match ($this) {
            $this::SUPER_ADMIN => 'Super Admin',
            $this::ADMIN => 'Admin',
            $this::MODERATOR => 'Moderator',
        };
    }
}
   