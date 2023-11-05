<?php

namespace App\Controller;


use App\Entity\Register;
use App\Form\RegisterType;
use App\Form\UpdateAvatarType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class RegisterController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * function list inscrits 
     *
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/inscrit/list', name: 'app_inscrits', methods: ['GET'])]
    public function listInscrit(PaginatorInterface $paginator, Request $request): Response
    {
        $lists = $paginator->paginate($this->em->getRepository(Register::class)->findAll(), $request->query->getInt('page', 1), 10);
        return $this->render('/pages/register/list.html.twig', ['lists' => $lists]);
    }


    /**
     * function new inscrit
     *
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    #[Route('/inscrit/new', name: 'app_inscrit_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function newInscrit(Request $request, ValidatorInterface $validator): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_login');
        }
        if ($this->getUser()->isConfirmed() === true) {
            $this->addFlash('success', 'Your account already exists !');
            return $this->redirectToRoute('app_main');
        }
        $register = new Register();
        $form = $this->createForm(RegisterType::class, $register);
        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            $errors = $validator->validate($register);
            if (count($errors) > 0) {
                return $this->render('/pages/register/new.html.twig', ['form' => $form->createView(), 'errors' => $errors]);
            }
            if ($form->isSubmitted() && $form->isValid()) {
                $register->setUser($this->getUser());
                $register->setUser($this->getUser()->setConfirmed(true));
                $this->em->persist($register);
                $this->em->flush();
                $this->addFlash('success', 'Congratulation your account is ready now ! ');
                return $this->redirectToRoute('app_main');
            }
        }
        return $this->render('/pages/register/new.html.twig', ['form' => $form->createView()]);
    }
    #[Route('/update/avatar/{id}', name: 'app_update_avatar', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function updatePseudoAvatar(Request $request, Register $register, ValidatorInterface $validator): Response
    {
        $control = $this->em->getRepository(Register::class)->find($register);
        if ($this->getUser()->getId() === $control->getUser()->getId()) {
            $form = $this->createForm(UpdateAvatarType::class, $register);
            $form->handleRequest($request);
            if ($request->isMethod('POST')) {
                $errors = $validator->validate($register);
                if (count($errors) > 0) {
                    return $this->render("pages/register/modif_avatar", ['form' => $form->createView(), 'errors' => $errors]);
                }
                if ($form->isSubmitted() && $form->isValid()) {
                    $this->em->persist($register);
                    $this->em->flush();
                    $this->addFlash('success', 'Your profil has been updated');
                    return $this->redirectToRoute('app_main');
                }
            }
            return $this->render('/pages/register/modif_avatar.html.twig', ['form' => $form->createView()]);
        }
        return $this->redirectToRoute('app_login');
    }
}
