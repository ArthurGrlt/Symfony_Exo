<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    public function __construct(UsersRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        $users = $this->userRepository->findAll();

        $newUser = new Users();
        $form = $this->createForm(UsersType::class, $newUser);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $newUser = $form->getData();
            $entityManager->persist($newUser);
            $entityManager->flush();

            return $this->redirectToRoute('home');

        }

        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
            'userForm' => $form->createView(),
            'users' => $users,
            'onPage' => 'home'
        ]);
    }

    /**
     * @Route("/users/update/{id}", name="updateUser")
     */
    public function update($id, Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->userRepository->find($id);

        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $newUser = $form->getData();
            $entityManager->persist($newUser);
            $entityManager->flush();

            return $this->redirectToRoute('home');

        }

        return $this->render('users/update.html.twig', [
            'controller_name' => 'UsersController',
            'userForm' => $form->createView(),
            'user' => $user,
            'onPage' => ''
        ]);
    }
}
