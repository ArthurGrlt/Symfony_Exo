<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Tasks;
use App\Form\TasksType;
use App\Repository\TasksRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TasksController extends AbstractController
{
    public function __construct(TasksRepository $taskRepository, UsersRepository $userRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/tasks", name="tasks")
     */
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        $tasks = $this->taskRepository->findAllOrderByUsers();
        $users = $this->userRepository->findAll();

        $newTask = new Tasks();
        $form = $this->createForm(TasksType::class, $newTask);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $user = $this->userRepository->find($request->request->get('userId'));

            $newTask = $form->getData();
            $newTask->setUserId($user);
            $entityManager->persist($newTask);
            $entityManager->flush();

            return $this->redirectToRoute('tasks');
        }

        return $this->render('tasks/index.html.twig', [
            'controller_name' => 'TasksController',
            'taskForm' => $form->createView(),
            'users' => $users,
            'tasks' => $tasks,
            'onPage' => 'tasks'
        ]);
    }

     /**
     * @Route("/tasks/update/{id}", name="updateTask")
     */
    public function update($id, Request $request, EntityManagerInterface $entityManager)
    {
        $users = $this->userRepository->findAll();
        $task = $this->taskRepository->find($id);

        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $user = $this->userRepository->find($request->request->get('userId'));

            $newTask = $form->getData();
            $newTask->setUserId($user);
            $entityManager->persist($newTask);
            $entityManager->flush();

            return $this->redirectToRoute('tasks');
        }

        return $this->render('tasks/update.html.twig', [
            'controller_name' => 'TasksController',
            'taskForm' => $form->createView(),
            'users' => $users,
            'task' => $task,
            'onPage' => ''
        ]);
    }

    /**
     * @Route("/tasks/remove/{id}", name="removeTask")
     */
    public function remove($id, EntityManagerInterface $entityManager)
    {
        $task = $this->taskRepository->find($id);
        $userId = $task->getUserId()->getId();

        $entityManager->remove($task);
        $entityManager->flush();
        return $this->redirectToRoute('updateUser', ['id' => $userId]);
    }

    /**
     * @Route("/tasks/changestate/{id}", name="changeState")
     */
    public function changeState($id, EntityManagerInterface $entityManager)
    {
        $task = $this->taskRepository->find($id);
        $userId = $task->getUserId()->getId();

        if($task->getState()) {
            $task->setState(false);
        } else {
            $task->setState(true);
        }

        $entityManager->persist($task);
        $entityManager->flush();
        return $this->redirectToRoute('updateUser', ['id' => $userId]);
    }
}
