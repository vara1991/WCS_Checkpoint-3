<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tile;
use App\Repository\BoatRepository;
use App\Repository\TileRepository;
use App\Service\MapManagerService;

class MapController extends AbstractController
{
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
        ]);
    }

    /**
     * @Route("/mapi/", name="map_exists")
     */
    public function index(MapManagerService $mapManagerService)
    {
        $tile = $this->getDoctrine()
            ->getRepository(Tile::class)
            ->findOneBy(['id' => 3]);

        $x = $tile->getCoordX();
        $y = $tile->getCoordY();

        $exists = $mapManagerService->tileExists($x, $y);

        $outsideMap = $mapManagerService->tileExists(13, 4);


        return $this->render('map/mapi.html.twig', [
            'x' => $x,
            'y' =>$y,
            'exists' => $exists,
            'outsideMap' => $outsideMap
        ]);
    }
}
