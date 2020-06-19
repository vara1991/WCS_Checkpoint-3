<?php


namespace App\service;


use App\Entity\Boat;
use App\Entity\Tile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MapManager extends AbstractController
{
    public function tileExists(int $x,int $y)
    {
        if (0 <= $x && $x <= 11 && 0 <= $y && $y<= 5) {
            return true;
        } else {
            return false;
        }
    }

    public function getRandomIsland()
    {
        $em = $this->getDoctrine()->getManager();
        $islandTiles = $em->getRepository(Tile::class)->findBy(['type' => 'island']);
        foreach($islandTiles as $item) {
            $item->setHasTreasure(false);
        }
        $randIsland =  $islandTiles[array_rand($islandTiles)];
        $randIsland->sethastreasure(true);
        return $randIsland;
    }

    public function checkTreasure(Boat $boat)
    {
        $em = $this->getDoctrine()->getManager();
        $treasure = $em->getRepository(Tile::class)->findOneBy(['hasTreasure' => 1]);
        if ($treasure->getcoordX() === $boat->getcoordX() && $treasure->getcoordY() === $boat->getcoordY()){
            return true;
        } else {
            return false;
        }
    }
}