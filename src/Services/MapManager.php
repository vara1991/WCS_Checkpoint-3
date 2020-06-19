<?php


namespace App\Services;


use App\Repository\TileRepository;

class MapManager
{
    public function tileExists(int $x, int $y)
    {
        if ($y > 5 || $y < 0 || ($x > 11 || $x < 0)) {
            return false;
        } else {
            return true;
        }
    }
}