<?php

namespace App\DataFixtures;

use App\Entity\Taches;
use DateTime;
use DateTimeZone;
use App\Entity\Task;
use PhpParser\Node\Expr\Cast\Object_;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\DocBlock\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Driver\IBMDB2\Exception\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        

        // Récupération des catégories créées
        $allTags = $manager->getRepository(Tag::class)->findAll();

        // Création entre 15 et 30 tâches aléatoirement
        for ($t = 0; $t < mt_rand(15, 30); $t++) {
            // création nouvel objet Task
            $task = new Taches;
            // on le nourrit
            $task->setTitle()
                ->setDescription()
                ->setCreatedAt(new \DateTime)
                ->setDone(); // attention les dates sont créées en fonction du réglage serveur

            $manager->persist($task);
        }

        $manager->flush();
    }
}

