<?php

namespace App\Controller;

use App\Entity\Boat;
use App\Form\BoatType;
use App\Repository\BoatRepository;
use App\Service\MapManager;
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
    private $boatRepository;
    private $mapManager;
    private $em;

    public function __construct(BoatRepository $boatRepository, EntityManagerInterface $em, MapManager $mapManager){
        $this->boatRepository = $boatRepository;
        $this->mapManager = $mapManager;
        $this->em = $em;
    }

    /**
     * Move the boat to coord x,y
     * @Route("/move/{x}/{y}", name="moveBoat", requirements={"x"="\d+", "y"="\d+"}))
     */
    public function moveBoat(int $x, int $y) :Response
    {
        $boat = $this->boatRepository->findOneBy([]);
        $boat->setCoordX($x);
        $boat->setCoordY($y);

        $this->em->flush();

        return $this->redirectToRoute('map');
    }

     /**
     * Move the boat to direction N|S|E|W
     * @Route("/move/{direction}", name="moveDirection", requirements={"direction"="N|S|E|W"})
     */
    public function moveDirection($direction) :Response
    {
        $boat = $this->boatRepository->findOneBy([]);
        if($direction === "N"){
            $boat->setCoordY($boat->getCoordY() - 1);
        }
        if($direction === "S"){
            $boat->setCoordY($boat->getCoordY() + 1);
        }
        if($direction === "E"){
            $boat->setCoordX($boat->getCoordX() + 1);
        }
        if($direction === "W"){
            $boat->setCoordX($boat->getCoordX() - 1);
        }
        if($this->mapManager->tileExist($boat->getCoordX(), $boat->getCoordY()) === true){
            if($this->mapManager->checkTreasure($boat) === true){
                $this->addFlash('success', 'Treasure found !');
            }
            $this->em->flush();
        } else {
            $this->addFlash('warning', 'Map Not Found !');
        }
        return $this->redirectToRoute('map');
    }

    /**
     * @Route("/", name="boat_index", methods="GET")
     */
    public function index(): Response
    {
        return $this->render('boat/index.html.twig', ['boats' => $this->boatRepository->findAll()]);
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
}
