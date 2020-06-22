<?php


namespace App\Services;
use App\Repository\BoatRepository;
use App\Repository\TileRepository;


class MapManager
{
    public function tileExists($x, $y)
    {
            if ($x > 11 || $x < 0){
                return false;
            }elseif ($y > 5 || $y < 0){
                return false;
            }else{
                return true;
            }
    }

}