<?php

namespace App\Controller;

use App\Entity\Boat;
use App\Form\BoatType;
use App\Repository\BoatRepository;
use App\Repository\TileRepository;
use App\Services\MapManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/boat")
 */
class BoatController extends AbstractController
{

    /**
     * Move the boat to coord x,y
     * @Route("/move/{x}/{y}", name="moveBoat", requirements={"x"="\d+", "y"="\d+"}))
     */
    public function moveBoat(int $x, int $y, BoatRepository $boatRepository, EntityManagerInterface $em) :Response
    {
        $boat = $boatRepository->findOneBy([]);
        $boat->setCoordX($x);
        $boat->setCoordY($y);

        $em->flush();

        return $this->redirectToRoute('map');
    }


    /**
     * @Route("/", name="boat_index", methods="GET")
     */
    public function index(BoatRepository $boatRepository): Response
    {
        return $this->render('boat/index.html.twig', ['boats' => $boatRepository->findAll()]);
    }

    /**
     * @Route("/new", name="boat_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $boat = new Boat();
        $form = $this->createForm(BoatType::class, $boat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($boat);
            $em->flush();

            return $this->redirectToRoute('boat_index');
        }

        return $this->render('boat/new.html.twig', [
            'boat' => $boat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="boat_show", methods="GET")
     */
    public function show(Boat $boat): Response
    {
        return $this->render('boat/show.html.twig', ['boat' => $boat]);
    }

    /**
     * @Route("/{id}/edit", name="boat_edit", methods="GET|POST")
     */
    public function edit(Request $request, Boat $boat): Response
    {
        $form = $this->createForm(BoatType::class, $boat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('boat_index', ['id' => $boat->getId()]);
        }

        return $this->render('boat/edit.html.twig', [
            'boat' => $boat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="boat_delete", methods="DELETE")
     */
    public function delete(Request $request, Boat $boat): Response
    {
        if ($this->isCsrfTokenValid('delete' . $boat->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($boat);
            $em->flush();
        }

        return $this->redirectToRoute('boat_index');
    }

    /**
     * @Route("/direction/{param}", name="boat_direction")
     * @param $param
     * @param BoatRepository $boatRepository
     * @param EntityManagerInterface $em
     * @param MapManager $mapManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function moveDirection($param, BoatRepository $boatRepository, EntityManagerInterface $em, MapManager $mapManager, TileRepository $tileRepository)
    {
        $boat = $boatRepository->findOneBy([]);
        $positionY = $boat->getCoordY();
        $positionX = $boat->getCoordX();
        if ($param === "N") {
            $positionY += -1;
            $boat->setCoordY($positionY);
            $result = $mapManager->tileExists($boat->getCoordX(), $boat->getCoordY());
            if ($result === true){
                $em->flush();
            }else{
                echo $error = "Wrong destination Jack ! Come back";
            }

            return $this->redirectToRoute('map');

        }elseif ($param === "S") {
            $positionY += +1;
            $boat->setCoordY($positionY);
            $result = $mapManager->tileExists($boat->getCoordX(), $boat->getCoordY());
            if ($result === true){
                $em->flush();
            }else{
                echo $error = "Wrong destination Jack ! Come back";
            }

            return $this->redirectToRoute('map');

        }elseif ($param === "W") {
            $positionX += -1;
            $boat->setCoordX($positionX);
            $result = $mapManager->tileExists($boat->getCoordX(), $boat->getCoordY());
            if ($result === true){
                $em->flush();
            }else{
                echo $error = "Wrong destination Jack ! Come back";
            }

            return $this->redirectToRoute('map');

        }elseif ($param === "E") {
            $positionX += +1;
            $boat->setCoordX($positionX);
            $result = $mapManager->tileExists($boat->getCoordX(), $boat->getCoordY());
            if ($result === true){
                $em->flush();
            }else{
                echo $error = "Wrong destination Jack ! Come back";
            }

            return $this->redirectToRoute('map');

        }else{
            return $this->redirectToRoute('map');
        }
    }
}
