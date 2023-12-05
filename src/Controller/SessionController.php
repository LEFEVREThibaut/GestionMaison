<?php

namespace App\Controller;

use App\Entity\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use App\Entity\Taches;

class SessionController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/session', name: 'app_session')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Session::class);

        $dernierElement = $repository->findOneBy([], ['id' => 'desc']);

        // Vérifier si l'élément existe et récupérer la valeur de l'attribut 'done'
        if ($dernierElement instanceof Session) {
            $done = $dernierElement->isDone();
            if ($done != 0) {
                $session = new Session();
                $session->setDuration(new \DateTime());
                $session->setDone(false);
                $session->setCreatedAt(new \DateTimeImmutable());
            } else {
                $session = $dernierElement;
            }
        }
        $sessions = $entityManager->getRepository(Session::class)->findBySessions();


        $role = $this->getUser()->isAdmin();

        $entityManager->persist($session);
        $entityManager->flush();

        $tasks = $entityManager->getRepository(Taches::class)->findAll();

        // voir le redirect route sans le return
        // return $this->redirectToRoute('app_task');
        $this->redirectToRoute('app_task');
        return $this->render('task/index.html.twig', [
            'sessions' => $sessions,
            'tasks' => $tasks,
            'session' => $session,
            'role' => $role,
        ]);
    }

    #[Route('/session/{id}/set-done', name: 'app_session_set_done')]
    public function setDone(EntityManagerInterface $entityManager, $id): Response
    {
        $session = $entityManager->getRepository(Session::class)->find($id);
        $session->setDone(true);

        $t = new \DateTime();
        $fin = $t->getTimestamp();
        $debut = $session->getDuration()->getTimestamp();
        $difference = $fin - $debut;

        $heures = floor(($difference % 3600) / 3600);
        $minutes = floor(($difference % 3600) / 60);
        $secondes = $difference % 60;

        $temps = $heures . ' h ' . $minutes . ' min ' . $secondes . ' s';

        $session->setFinalDuration($temps);
        $entityManager->flush();

        $tasks = $entityManager->getRepository(Taches::class)->findAll();
        $role = $this->getUser()->isAdmin();

        return $this->redirectToRoute('app_task');

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
            'session' => $session,
            'role' => $role,
            'done' => $temps,
        ]);
    }

}
// htmx -> js en annotation
// app.user.roles
