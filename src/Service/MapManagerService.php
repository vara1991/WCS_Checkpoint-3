<?php


namespace App\Service;

use App\Repository\TileRepository;
use App\Entity\Tile;

class MapManagerService
{
    private $tileRepository;

    public function __construct(TileRepository $tileRepository)
    {
        $this->tileRepository = $tileRepository;
    }

    public function tileExists(int $x, int $y) : bool
    {
        if ($x >= 12) {
            return false;
        } elseif ($x <= -1) {
            return false;
        } else {
            return true;
        }

        if ($y >= 6) {
            return false;
        } elseif ($y <= -1) {
            return false;
        } else {
            return true;
        }
    }
}