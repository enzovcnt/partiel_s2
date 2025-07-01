<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Screening;
use App\Form\ReservationForm;
use App\Form\ScreeningForm;
use App\Repository\ScreeningRepository;
use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ScreeningController extends AbstractController
{
    #[Route('/screening', name: 'app_screening')]
    public function index(ScreeningRepository $screeningRepository): Response
    {
        return $this->render('screening/index.html.twig', [
            'screenings' => $screeningRepository->findAll(),
        ]);
    }

    #[Route('/screening/show/{id}', name: 'app_show_screening', priority: -1)]
    public function show(Screening $screening): Response
    {
        return $this->render('screening/show.html.twig', [
            'screening' => $screening,
        ]);
    }

    #[Route('/screening/new', name: 'app_screening_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $screening = new Screening();
        $form = $this->createForm(ScreeningForm::class, $screening);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($screening);
            $manager->flush();
            return $this->redirectToRoute('app_show_screening', ['id' => $screening->getId()]);
        }
        return $this->render('screening/create.html.twig', [
            'screening' => $screening,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/screening/{id}/edit', name: 'app_screening_edit')]
    public function edit(Request $request, Screening $screening, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(ScreeningForm::class, $screening);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            return $this->redirectToRoute('app_show_screening', ['id' => $screening->getId()]);
        }
        return $this->render('screening/edit.html.twig', [
            'screening' => $screening,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/screening/{id}/delete', name: 'app_screening_delete')]
    public function delete(Screening $screening
        , EntityManagerInterface $manager): Response
    {

        $manager->remove($screening);
        $manager->flush();
        return $this->redirectToRoute('app_screening');
    }

    #[Route('/screening/{id}/reservation', name: 'app_screening_reservation')]
    public function reservation(
        Screening $screening,
        EntityManagerInterface $manager,
        Request $request,
        StripeService $service,
    ): Response
    {
        $user = $this->getUser();
        $reservation = new Reservation();
        $reservation->setScreening($screening);
        $reservation->setOfUser($user);
        $reservation->setCreatedAt(new \DateTime('now'));

        $form = $this->createForm(ReservationForm::class, $reservation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reserveSeats = $reservation->getNumberOfSeats();
            $reservation->setPaid(false);
            $manager->persist($reservation);
            $manager->flush();

            $session = $service->createCheckoutSession(
                $screening->getPrice(),
                $reserveSeats,
                $screening->getFilm()->getName(),
                $this->generateUrl('app_payment_success', ['reservationId' => $reservation->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
                $this->generateUrl('app_payment_cancel', ['reservationId' => $reservation->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            );



            return $this->redirect($session->url);        }

        return $this->render('screening/reservation.html.twig', [
            'screening' => $screening,
            'available' => $screening->getAvailableSeats(),
            'form' => $form->createView(),
        ]);
    }
}
