<?php

namespace App\Services;

use App\Repository\TileRepository;

class MapManagerService {
    public function tileExists($x, $y, TileRepository $tileRepository)
    {
        $tile = $tileRepository->findOneBy([]);
        if($x<0 || $x>11 || $y<0 || $y>5){
            return false;
        } else {
            return true;
        }
    }

    public function getRandomIsland(TileRepository $tileRepository)
   {
        $islandTiles = $tileRepository->findBy(['type' => 'island']);
        return $randomTile = array_rand($islandTiles,1);
   }

}