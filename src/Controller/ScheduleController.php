<?php

namespace App\Controller;

use App\Entity\Schedule;
use App\Form\ScheduleForm;
use App\Repository\ScheduleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ScheduleController extends AbstractController
{

    #[Route('/schedule', name: 'app_schedule')]
    public function new(Request $request, EntityManagerInterface $manager,ScheduleRepository $scheduleRepository): Response
    {
        $schedule = new Schedule();
        $form = $this->createForm(ScheduleForm::class, $schedule);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($schedule);
            $manager->flush();
            return $this->redirectToRoute('app_schedule');
        }

        return $this->render('schedule/index.html.twig', [
            'form' =>  $form->createView(),
            'schedules' => $scheduleRepository->findAll(),
        ]);

    }

    #[Route('/schedule/{id}/delete', name: 'app_schedule_delete')]
    public function delete(Schedule $schedule, EntityManagerInterface $manager): Response
    {

        $manager->remove($schedule);
        $manager->flush();
        return $this->redirectToRoute('app_schedule');
    }
}
