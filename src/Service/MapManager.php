<?php 

namespace App\Service;

use App\Entity\Boat;
use App\Entity\Tile;
use App\Repository\TileRepository;
use Doctrine\ORM\EntityManagerInterface;

class MapManager {

    private $tileRepository;
    private $em;

    public function __construct(TileRepository $tileRepository, EntityManagerInterface $em)
    {
        $this->tileRepository = $tileRepository;
        $this->em = $em;
    }

    public function tileExist(int $x, int $y):bool
    {
        $X = [];
        $Y = [];

        $tiles = $this->tileRepository->findAll();
        foreach($tiles as $tile){
            $X[] = $tile->getCoordX();
            $Y[] = $tile->getCoordY();
        }
        if(in_array($x, $X) && in_array($y, $Y)){
            return true;
        }
        return false;
    }

    public function getTileType(int $x, int $y):string
    {
        foreach($this->tileRepository->findAll() as $tile){
            if($tile->getCoordX() === $x && $tile->getCoordY() === $y){
                return $tile->getType();
            }
        }
    }

    public function randomIsland()
    {
        $islands = $this->tileRepository->findBy(['type' => 'island']);
        foreach($islands as $island){
            $island->setHasTreasure(false);
        }
        $island = $islands[array_rand($islands, 1)]->setHasTreasure(true);
        $this->em->flush();
        return $island;
    }

    public function checkTreasure(Boat $boat){
        $treasureTile = $this->tileRepository->findOneBy(["has_treasure" => true]);
        if(($boat->getCoordY() === $treasureTile->getCoordY()) && ($boat->getCoordX() === $treasureTile->getCoordX())) return true;
        return false;
    }
}