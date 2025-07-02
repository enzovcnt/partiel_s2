<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Screening;
use App\Entity\User;
use App\Repository\ReservationRepository;
use App\Service\MailReservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(ReservationRepository $repository): Response
    {

        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour voir vos réservations.');
            return $this->redirectToRoute('app_login');
        }

        $reservations = $repository->findBy(['ofUser' => $user]);

        return $this->render('reservation/index.html.twig', [
            'reservation' => $reservations,
        ]);
    }

    #[Route('/reservation/{id}/delete', name:'app_reservation_delete')]
    public function delete(
        Reservation $reservation,
        EntityManagerInterface $manager,
        MailReservation $mailReservation
    ): Response
    {
        $screening = $reservation->getScreening();
        $schedule = $screening->getSchedule();

        // On reconstruction la date et l'heure en utilisant schedule pour que ça corresponde
        $startDateTime = (clone $schedule->getDate())
            ->setTime(
                (int) $schedule->getHours()->format('H'),
                (int) $schedule->getHours()->format('i'),
                (int) $schedule->getHours()->format('s')
            );

        $now = new \DateTime();
        $intervalInSeconds = $startDateTime->getTimestamp() - $now->getTimestamp();


        if ($intervalInSeconds <= 600) {
            //attention à bien avoir le bon fuseaux horaire
            $mailReservation->sendNoReservationCancellationEmail($reservation->getOfUser(), $screening);

            $this->addFlash('error', 'L’annulation n’est plus possible à moins de 10 minutes avant la séance.');

            return $this->redirectToRoute('app_reservation');
        }


        $manager->remove($reservation);
        $manager->flush();

        $this->addFlash('success', 'Réservation annulée avec succès.');

        return $this->redirectToRoute('app_reservation');

    }
}
