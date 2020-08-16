<?php

namespace App\Controller;

use App\Entity\Boat;
use App\Repository\TileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tile;
use App\Repository\BoatRepository;
use App\Services\MapManager;

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
        $x = $boat->getCoordX();
        $y = $boat->getCoordY();

        return $this->render('map/index.html.twig', [
            'map'  => $map ?? [],
            'boat' => $boat,
            'x'=>$x,
            'y'=>$y,
        ]);
    }

    /**
     * @Route("/start", name="start")
     */
    public function start(EntityManagerInterface $em, BoatRepository $boatRepository,MapManager $mapManager)
    {
        $mapManager->getRandomIsland();
        $boat = $boatRepository->findOneBy([]);
        $x = $boat->setCoordX(0);
        $y = $boat->setCoordY(0);

        $em->flush();

        return $this->redirectToRoute('map');
    }
}
