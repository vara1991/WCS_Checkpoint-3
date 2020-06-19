<?php


namespace App\Services;



use phpDocumentor\Reflection\Types\Boolean;

class MapManager
{

    public function tileExists(int $x, int $y, TileRepository $tileRepository): Boolean
    {
        if ($this->$tileRepository->getTileExists($x, $y)) ;

        return false;
    }

    public function  moveDirection(int $x, int $y)
    {
        if ($this->moveDirection());

        return $this->render('tile/base.html.twig');
    }
}