<?php

namespace App\Controller;

use DateTime;
use App\Entity\Taches;
use App\Form\TaskType;
use App\Entity\Session;
use App\Repository\TachesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class TaskController extends AbstractController
{

    protected TachesRepository $taskRepository;
    protected EntityManagerInterface $manager;

    public function __construct(TachesRepository $taskRepository, EntityManagerInterface $manager)
    {
        $this->taskRepository = $taskRepository;
        $this->manager = $manager;
    }


    #[Route('/task', name: 'app_task')]
    public function index(): Response
    {
        $role = $this->getUser()->isAdmin();
        
        $sessions = $this->manager->getRepository(Session::class)->findBySessions();
        $tasks = $this->taskRepository->findAll();

        return $this->render('task/index.html.twig', [
            'sessions' => $sessions,
            'role' => $role,
            'controller_name' => 'TaskController',
            'tasks' => $tasks
        ]);
    }

    #[Route('/task/create', name: 'app_task_create')]
    #[Route('/task/update/{id}', name: 'app_task_update', requirements: ['id' => "\d+"])]
    public function task(Taches $task = null, Request $request): Response
    {
        if (!$task) {
            $task = new Taches;
            $task->setCreatedAt(new DateTime);
        }

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $task->setName($form->get('name')->getData())
            //     ->setDescription($form->get('description')->getData())
            //     ->setDueAt($form->get('dueAt')->getData())
            //     ->setTag($form->get('tag')->getData());


            $this->manager->persist($task);
            $this->manager->flush();

            return $this->redirectToRoute('app_task');
        }

        return $this->render('task/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/task/delete/{id}', name: 'app_task_delete', requirements: ['id' => "\d+"])]
    public function deleteTask(Taches $task): Response
    {
        $this->manager->remove($task);
        $this->manager->flush();

        return $this->redirectToRoute('app_task');
    }

    #[Route('/task/done/{id}', name: 'app_task_done', requirements: ['id' => "\d+"])]
    public function doneTask($id): Response
    {
        $t = $this->taskRepository->find($id);
        $t->setDone(true);
        $this->manager->flush();

        return $this->redirectToRoute('app_task');
    }
}
