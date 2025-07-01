<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Screening;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function reserveSeats(Screening $screening, User $user, int $requestedSeats, EntityManagerInterface $manager): Response
    {
        return $this->redirectToRoute('app_screening');
    }
}
