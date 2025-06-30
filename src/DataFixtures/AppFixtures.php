<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Dub;
use App\Entity\Film;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $categories = [];
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setName($faker->word);
            $manager->persist($category);
            $categories[] = $category;
        }

        $dubs = [];
        foreach (['VF', 'VO', 'VOSTFR'] as $version) {
            $dub = new Dub();
            $dub->setVersion($version);
            $manager->persist($dub);
            $dubs[] = $dub;
        }

        for ($i = 0; $i < 20; $i++) {
            $film = new Film();
            $film->setName($faker->sentence(3));
            $film->setSummary($faker->paragraph(3));


            $film->setCategory($faker->randomElement($categories));

            $film->setDub($faker->randomElement($dubs));



            for ($j = 0; $j < mt_rand(1, 3); $j++) {
                $image = new Image();
                $image->setImageName('dummy.jpg');
                $image->setFilm($film);
                $manager->persist($image);
                $film->addImage($image);
            }


            $manager->persist($film);
        }

        $manager->flush();
    }
}
