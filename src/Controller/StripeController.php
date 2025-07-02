<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Screening;

use App\Repository\ReservationRepository;
use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class StripeController extends AbstractController
{
    #[Route('/payment/success/{reservationId}', name: 'app_payment_success')]
    public function success(
        int $reservationId,
        ReservationRepository $reservationRepository,
        EntityManagerInterface $manager
    ): Response
    {
        //chercher l'id manuellement sinon trouve pas
        $reservation = $reservationRepository->find($reservationId);
        if (!$reservation) {
            throw $this->createNotFoundException('Réservation non trouvée.');
        }
        $reservation->setPaid(true);
        $manager->flush();

        $this->addFlash('success', 'Paiement validé ! Votre réservation est confirmée.');

        return $this->redirectToRoute('app_home');
    }

    #[Route('/payment/cancel/{reservationId}', name: 'app_payment_cancel')]
    public function cancel(Reservation $reservation): Response
    {
        $this->addFlash('warning', 'Le paiement a été annulé.');

        return $this->redirectToRoute('app_screening_reservation', ['id' => $reservation->getScreening()->getId()]);
    }

}
