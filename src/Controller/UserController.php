<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\ModifAvatarType;
use App\Form\UpdateProfilType;
use App\Form\UserPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    /**
     * list users
     * @return Response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/users', name: 'app_user', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, UserRepository $repository, Request $request): Response
    {
        $users = $paginator->paginate($repository->findAllUsers(), $request->query->getInt('page', 1), 10);
        return $this->render('pages/user/list.html.twig', [
            'users' => $users
        ]);
    }
    /**
     * show user
     * @param EntityManagerInterface $em
     * @param UserRepository $user
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/user/show/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(EntityManagerInterface $em, User $user, AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()->getId()=== $user->getId()) {
            $findUser = $em->getRepository(User::class)->find($user->getId());
            return $this->render('pages/user/show.html.twig', ['user' => $findUser]);
        } else {
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();
            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        }
    }

    /**
     * function update password
     *
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/user/edit-password/{id}', name: 'user_edit_password', methods: (['GET', 'POST']))]
    public function editPassword(User $user, Request $request, EntityManagerInterface $em, ValidatorInterface $validator, UserPasswordHasherInterface $hasher): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(UserPasswordType::class, $user);
        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                return $this->render("pages/user/edit_password.html.twig", ['form' => $form->createView(), 'errors' => $errors]);
            }
            if ($form->isSubmitted() && $form->isValid()) {
                if ($hasher->isPasswordValid($user, $form->getData()->getPlainPassword())) {
                    $user->setPassword(
                        $hasher->hashPassword($user, $form->getData()->getNewPassword())
                    );
                    $em->persist($user);
                    $em->flush();
                    $this->addFlash('success', 'password  has been modified !');
                    return $this->redirectToRoute('app_login');
                } else {
                    $this->addFlash('warning', 'password false');
                }
            }
        }
        return $this->render('pages/user/edit_password.html.twig', ['form' => $form->createView()]);
    }


    #[IsGranted('ROLE_USER')]
    #[Route('/user/edit-profil/{id}', name: 'user_edit_profil', methods: ['GET', 'POST'])]
    public function updateProfil(User $user, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(ModifAvatarType::class, $user);
        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                $user->setPassword($user->getPassword());
                $em->persist($user);
                $em->flush();
                $this->addflash('success', 'profil has been modified.');
                return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
            }
        }

        return $this->render('pages/user/edit_avatar.html.twig', ['form' => $form->createView()]);
    }
}
