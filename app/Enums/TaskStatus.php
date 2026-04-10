<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in progress';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING     => 'Pending',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED   => 'Completed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING     => 'warning',
            self::IN_PROGRESS => 'info',
            self::COMPLETED   => 'success',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::PENDING     => 'clock',
            self::IN_PROGRESS => 'loader',
            self::COMPLETED   => 'check',
        };
    }
}