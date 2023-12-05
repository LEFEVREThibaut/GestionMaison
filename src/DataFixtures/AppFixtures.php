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
        

        
        // faire en sorte que l'utilisateur avec l'id 1 ai le rôle admin
        $user = $manager->getRepository(User::class)->find(1);
        var_dump($user);
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);


        $manager->flush();
    }
}