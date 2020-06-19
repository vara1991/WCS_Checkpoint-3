<?php

namespace App\Services;

use App\Repository\TileRepository;

class MapManager
{
    public function tileExists ($x, $y)
    {

        if(0 <= $x && $x <= 11 && 0 <= $y && $y<= 5) {
            return true;
        }else{
            return false;
        }
    }

    public function getRandomIsland()
    {

    }
}