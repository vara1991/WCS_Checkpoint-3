<?php

namespace App\Services;

use App\Entity\Boat;
use App\Entity\Tile;
use Doctrine\ORM\EntityManagerInterface;

class MapManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

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
        $island = $this->em->getRepository(Tile::class)->findBy(['type' => 'island']);
        foreach ($island as $item){
            $item->setHasTreasure(false);
        }
        $random_key = array_rand($island);
        $random_value = $island[$random_key];
        $random_value->setHasTreasure(true);

        return $random_value;
    }

    public function checkTreasure($boat)
    {
        $treasure = $this->em->getRepository(Tile::class)->findOneBy(['hasTreasure' => true]);
        if ($treasure->getCoordY() === $boat->getCoordY() && $treasure->getCoordX() === $boat->getCoordX()) {
            return true;
        } else {
            return false;
        }
    }
}