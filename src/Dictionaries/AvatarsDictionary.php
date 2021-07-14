<?php

namespace App\Dictionaries;

class AvatarsDictionary
{
    /**
     * @return string[]
     */
    public static function get(): array
    {
        return [
            1 => '/img/male.jpeg',
            2 => '/img/female.jpg',
            3 => '/img/unknown.jpeg'
        ];
    }
}
