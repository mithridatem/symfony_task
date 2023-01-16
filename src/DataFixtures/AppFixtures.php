<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Task;
use App\Entity\Util;
use App\Entity\Category;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //instance de la librairie faker
        $faker = Faker\Factory::create('fr_FR');
        //variables pour stocker les utilisateurs
        $tabUtils = [];
        //variables pour stocker les categories
        $tabCats = [];
        //boucle pour ajouter 10 utilisateurs
        for ($i=0; $i < 10; $i++) { 
            $util = new Util();
            $util->setName($faker->lastName());
            $util->setFirstName($faker->firstName($gender = 'male'|'female'));
            $util->setMail($faker->freeEmail());
            $util->setPassword(password_hash($faker->word(), PASSWORD_DEFAULT));
            //ajoute les utilisateurs au tableau
            $tabUtils[] = $util;
            //stocker les objets
            $manager->persist($util);
        }
        //boucle pour ajouter 20 categories
        for ($i=0; $i < 20 ; $i++) { 
            $cat = new Category();
            $cat->setName($faker->jobTitle());
            //ajoute les categories au tableau
            $tabCats[] = $cat;
            //stocker les objets
            $manager->persist($cat);
        }
        //boucle pour ajouter 100 taches
        for ($i=0; $i < 100; $i++) { 
            $task = new Task();
            $task->setName($faker->jobTitle());
            $task->setContent($faker->text(40));
            $task->setDate($faker->dateTime());
            $task->setCompleted($faker->boolean());
            $task->setUtil($tabUtils[$faker->numberBetween(0, 9)]);
            $task->addCategory($tabCats[$faker->numberBetween(0, 9)]);
            $task->addCategory($tabCats[$faker->numberBetween(10, 19)]);
            //stocker les objets
            $manager->persist($task);
        }
        //pour insérer en BDD les enregistrements
        $manager->flush();
    }
}
