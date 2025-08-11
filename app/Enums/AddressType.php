<?php

namespace App\Enums;

enum AddressType: int
{
    case HOME = 1;
    case OFFICE = 2;


    public function title()
    {
        return match ($this) {
            $this::HOME => 'Home',
            $this::OFFICE => 'Office',
        };
    }

    public function label()
    {
        return match ($this) {
            $this::HOME => 'home',
            $this::OFFICE => 'office',
        };
    }
}
