<?php

enum Status: string
{
    case Available = 'Available';
    case Sold = 'Sold';

    public function label(): string
    {
        return static::getLabel($this);
    }
    public static function getLabel(self $value): string
    {
        return match ($value) {
            Status::Available => 'Available',
            Status::Sold => 'Sold',
        };
    }
}
