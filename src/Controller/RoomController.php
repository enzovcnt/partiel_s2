<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomForm;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RoomController extends AbstractController
{
    #[Route('/rooms', name: 'app_rooms')]
    public function index(RoomRepository $roomRepository): Response
    {
        return $this->render('room/index.html.twig', [
            'rooms' => $roomRepository->findAll(),
        ]);
    }

    #[Route('/room/{id}', name: 'app_room_show', priority: -1)]
    public function show(Room $room): Response
    {
        return $this->render('room/show.html.twig', [
            'room' => $room,
        ]);
    }

    #[Route('/room/new', name: 'app_room_new')]
    public function new(Request $request, EntityManagerInterface $manager, RoomRepository $repository): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        if (!in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_login');
        }
        $room = new Room();
        $form = $this->createForm(RoomForm::class, $room);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $existingRoom = $repository->findOneBy(['number' => $room->getNumber()]);
            if ($existingRoom !== null) {
                $this->addFlash('warning', 'Une salle avec ce numéro existe déjà.');
                return $this->redirectToRoute('app_room_new');
            }

            $manager->persist($room);
            $manager->flush();
            return $this->redirectToRoute('app_room_show', ['id' => $room->getId()]);
        }

        return $this->render('room/create.html.twig', [
            'form' =>  $form->createView(),
        ]);

    }

    #[Route('/room/{id}/edit', name: 'app_room_edit')]
    public function edit(Request $request, Room $room, EntityManagerInterface $manager): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        if (!in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(RoomForm::class, $room);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            return $this->redirectToRoute('app_room_show', ['id' => $room->getId()]);
        }
        return $this->render('room/edit.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/room/{id}/delete', name: 'app_room_delete')]
    public function delete(Room $room, EntityManagerInterface $manager): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        if (!in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_login');
        }

        $manager->remove($room);
        $manager->flush();
        return $this->redirectToRoute('app_rooms');
    }
}
