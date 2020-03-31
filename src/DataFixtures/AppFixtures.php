<?php

namespace App\DataFixtures;

use App\Entity\Cours;
use App\Entity\Semestre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load($manager)
    {
        $faker = Factory::create("fr_FR");
        $semestres = Array();
        for ($i=0; $i<6;$i++){
            $semestres[$i] = new Semestre();
            $semestres[$i]
                ->setNomFormation($faker->text(5))
                ->setNumeroSemestre($i+1);
            $manager->persist($semestres[$i]);
        }

        $cours = Array();
        for($i=0; $i<=60;$i++){
            $cours[$i] = new Cours();
            $cours[$i]
                ->setSemestre($semestres[$i%6])
                ->setNom($faker->text(10))
                ->setDescription($faker->paragraph);
            $manager->persist($cours[$i]);
        }

        $manager->flush();
    }
}
