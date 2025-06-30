<?php

namespace App\Controller;


use App\Entity\Dub;
use App\Form\DubForm;
use App\Repository\DubRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DubController extends AbstractController
{
    #[Route('/dub', name: 'app_dub')]
    public function index(DubRepository $repository, Request $request, EntityManagerInterface $manager): Response
    {

        $dub = new Dub();
        $form = $this->createForm(DubForm::class, $dub);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($dub);
            $manager->flush();
            return $this->redirectToRoute('app_dub');
        }

        return $this->render('dub/index.html.twig', [
            'dub' => $repository->findAll(),
            'form' => $form->createView(),
        ]);
    }
    //finir le crud pour DUB > au moins pouvoir tous les affichers, modifiers et supprimer sur la même page

}
