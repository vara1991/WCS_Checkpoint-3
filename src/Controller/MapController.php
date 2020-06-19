<?php

namespace App\Controller;

use App\Repository\TileRepository;
use App\service\MapManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tile;
use App\Repository\BoatRepository;

class MapController extends AbstractController
{
    /**
     * @Route("/map", name="map")
     * @param BoatRepository $boatRepository
     * @return Response
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
     * @Route("/start", name="start")
     * @param MapManager $mapManager
     * @param BoatRepository $boatRepository
     * @param TileRepository $tileRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function start(MapManager $mapManager, BoatRepository $boatRepository, TileRepository $tileRepository, EntityManagerInterface $em): Response
    {
        $em->flush();
        $boat = $boatRepository->findOneBy([]);
        $boat->setCoordX(0);
        $boat->setCoordY(0);
        $mapManager->getRandomIsland();
        $em->flush();

        return $this->redirectToRoute('map');
    }
}
