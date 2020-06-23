<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tile;
use App\Repository\BoatRepository;
use App\Service\MapManager;

class MapController extends AbstractController
{

    /**
     * @Route("/start", name="start")
     */
    public function start(BoatRepository $boatRepository, MapManager $mapManager)
    {
        $boat = $boatRepository->findOneBy([]);
        $boat->setCoordY(0)->setCoordX(0);
        $mapManager->randomIsland();

        return $this->redirectToRoute('map');
    }

    /**
     * @Route("/map", name="map")
     */
    public function displayMap(BoatRepository $boatRepository, MapManager $mapManager) :Response
    {
        $em = $this->getDoctrine()->getManager();
        $tiles = $em->getRepository(Tile::class)->findAll();

        foreach ($tiles as $tile) {
            $map[$tile->getCoordX()][$tile->getCoordY()] = $tile;
        }

        $boat = $boatRepository->findOneBy([]);
        $island = $mapManager->randomIsland();
    
        return $this->render('map/index.html.twig', [
            'map'  => $map ?? [],
            'boat' => $boat,
            'tile_type' => $mapManager->getTileType($boat->getCoordX(), $boat->getCoordY()) 
        ]);
    }
}
