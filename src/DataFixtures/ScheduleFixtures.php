<?php

namespace App\DataFixtures;

use App\Entity\Schedule;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ScheduleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //pour fixture > changer en DateTime et pas en DateTimeImmutable
        $days = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
        $hours = ['10:15', '13:45', '16:30', '19:00', '22:15'];

        foreach ($days as $day) {
            foreach ($hours as $hour) {
                if ($day === 'dimanche' && $hour !== '10:15') continue;

                $schedule = new Schedule();
                $schedule->setDays($day);
                $schedule->setHours(new \DateTime($hour));
                $manager->persist($schedule);
            }
        }

        $manager->flush();
    }
}
