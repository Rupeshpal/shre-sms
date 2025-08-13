<?php

namespace App\Enums;

enum StudentStatusEnum: string
{
    case Active = 'Active';
    case Passout = 'Passout';
    case Transferred = 'Transferred';
    case Suspended = 'Suspended';
    case Left = 'Left';
    case Pending = 'Pending';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
