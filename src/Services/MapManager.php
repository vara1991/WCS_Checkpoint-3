<?php


namespace App\Services;


use App\Repository\TileRepository;

class MapManager
{
    /**
     * @var bool
     */
    private $tileExists;

    public function tileExists(int $x, int $y, TileRepository $tileRepository)
    {
        $tileExists = $tileRepository->findAll();
        $x = $this->getCoordX($x);
        $y = $this->getCoordY($y);

        if (!null($x) && !null($y)) {
            return $tileExists = true;
        } else {
            return $tileExists = false;
        }
    }
}