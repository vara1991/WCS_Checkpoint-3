<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tile;
use App\Repository\BoatRepository;
use App\Services\MapManagerService;

class MapController extends AbstractController
{

    private $mapManagerService;

    public function __construct(MapManagerService $mapManagerService)
    {
        $this->mapManagerService = $mapManagerService;
    }

    /**
     * @Route("/map", name="map")
     */
    public function displayMap(BoatRepository $boatRepository) :Response
    {
        $em = $this->getDoctrine()->getManager();
        $tiles = $em->getRepository(Tile::class)->findAll();

        foreach ($tiles as $tile) {
            $map[$tile->getCoordX()][$tile->getCoordY()] = $tile;
        }

        $boat = $boatRepository->findOneBy([]);

        return $this->render('map/index.html.twig', [
            'map'  => $map ?? [],
            'boat' => $boat,
            'tile' => $tile
        ]);
    }

    /**
     * @Route("/start", name="start")
     */
    public function start(BoatRepository $boatRepository)
    {
        $boat = $boatRepository->findOneBy([]);
        $boat->setCoordX(0);
        $boat->setCoordY(0);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        //$this->mapManagerService->getRandomIsland($tile)->setHasTreasure();

        return $this->redirectToRoute('map');
    }

}
