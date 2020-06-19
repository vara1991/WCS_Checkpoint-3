<?php

namespace App\Controller;

use App\Entity\Boat;
use App\Form\BoatType;
use App\Repository\BoatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\MapManagerService;

/**
 * @Route("/boat")
 */
class BoatController extends AbstractController
{

    private $mapManagerService;

    public function __construct(MapManagerService $mapManagerService)
    {
        $this->mapManagerService = $mapManagerService;
    }

    /**
     * Move the boat to coord x,y
     * @Route("/move/{x}/{y}", name="moveBoat", requirements={"x"="\d+", "y"="\d+"}, ))
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
     * Move the boat
     * @Route("/direction/{d}", name="moveDirection", requirements={"d"="(N|W|S|E)"}))
     */
    public function moveDirection($d, BoatRepository $boatRepository, EntityManagerInterface $em): Response
    {
        $boat = $boatRepository->findOneBy([]);
        $x = $boat->getCoordX();
        $y = $boat->getCoordY();
        if($d === 'N'){
            $y--;
            $boat->setCoordX($x);
            $boat->setCoordY($y);
        }
        if($d === 'E'){
            $x++;
            $boat->setCoordX($x);
            $boat->setCoordY($y);
        }
        if($d === 'S'){
            $y++;
            $boat->setCoordX($x);
            $boat->setCoordY($y);
        }
        if($d === 'W'){
            $x--;
            $boat->setCoordX($x);
            $boat->setCoordY($y);
        }
        $em->flush();
        //if $this->mapManagerService->tileExists($x, $y){
           // $em->flush();
        //} else {
         //   flash massage
        //}
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
}
