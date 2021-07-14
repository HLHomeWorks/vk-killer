<?php

namespace App\Dictionaries;

class SexDictionary
{
    /**
     * @return string[]
     */
    public static function get(): array
    {
        return [
            1 => 'мужчина',
            2 => 'женщина',
            3 => 'неизвестно',
        ];
    }
}
