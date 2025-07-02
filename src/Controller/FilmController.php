<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Image;
use App\Form\FilmForm;
use App\Form\ImageForm;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FilmController extends AbstractController
{
    #[Route('/films', name: 'app_film')]
    public function index(FilmRepository $filmRepository): Response
    {
        return $this->render('film/index.html.twig', [
            'films' => $filmRepository->findAll(),
        ]);
    }

    #[Route('/film/{id}', name: 'app_film_show', priority: -1)]
    public function show(Film $film): Response
    {
        return $this->render('film/show.html.twig', [
            'film' => $film,
        ]);
    }

    #[Route('/film/new', name: 'app_film_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $film = new Film();
        $form = $this->createForm(FilmForm::class, $film);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($film);
            $manager->flush();
            return $this->redirectToRoute('app_film_show', ['id' => $film->getId()]);
        }

        return $this->render('film/create.html.twig', [
            'form' =>  $form->createView(),
        ]);

    }

    #[Route('/film/{id}/edit', name: 'app_film_edit')]
    public function edit(Request $request, Film $film, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(FilmForm::class, $film);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            return $this->redirectToRoute('app_film_show', ['id' => $film->getId()]);
        }
        return $this->render('film/edit.html.twig', [
            'film' => $film,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/film/{id}/delete', name: 'app_film_delete')]
    public function delete(Film $film, EntityManagerInterface $manager): Response
    {

        $manager->remove($film);
        $manager->flush();
        return $this->redirectToRoute('app_film');
    }

    #[Route('/film/addimage/{id}', name: 'app_film_addimage')]
    public function addImage(Film $film, Request $request, EntityManagerInterface $manager) : Response
    {

        $image = new Image();
        $formImage = $this->createForm(ImageForm::class, $image);
        $formImage->handleRequest($request);
        if($formImage->isSubmitted() && $formImage->isValid()){
            $image->setFilm($film);
            $manager->persist($image);
            $manager->flush();
            return $this->redirectToRoute('app_film_addimage', ['id' => $film->getId()]);
        }


        return $this->render('film/image.html.twig', [
            'film' => $film,
            'formImage' => $formImage->createView(),
        ]);
    }

    #[Route('/film/removeImage/{id}', name: 'app_removeImage')]
    public function removeImage(Image $image, EntityManagerInterface $manager) : Response
    {

        $postId = $image->getFilm()->getId();
        $manager->remove($image);
        $manager->flush();


        return $this->redirectToRoute('app_film_addimage', ['id' => $postId]);
    }
}
