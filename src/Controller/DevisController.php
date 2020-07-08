<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\User;
use App\Form\DevisType;
use App\Repository\DevisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/devis")
 */
class DevisController extends AbstractController
{
    /**
     * @Route("/", name="devis_index", methods={"GET"})
     */
    public function index(DevisRepository $devisRepository): Response
    {
        return $this->render('devis/index.html.twig', [
            'devis' => $devisRepository->findAll(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="devis_by_user", methods={"GET"})
     */
    public function devisByUser(User $user, DevisRepository $devisRepository): Response
    {
        return $this->render('devis/user_devis.html.twig', [
            'devis' => $devisRepository->findBy([
                'email' => $user->getEmail()
            ]),
        ]);
    }

    /**
     * @Route("/project/user/{id}", name="projects_by_user", methods={"GET"})
     */
    public function projectsByUser(User $user, DevisRepository $devisRepository): Response
    {
        return $this->render('devis/user_projects.html.twig', [
            'devis' => $devisRepository->findBy([
                'email' => $user->getEmail(),
                'isProject' => true
            ]),
        ]);
    }

    /**
     * @Route("/project/devis/{id}", name="convert_to_project", methods={"GET"})
     */
    public function convertToProject(Devis $devis, DevisRepository $devisRepository): Response
    {
        $devis->setIsProject(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        $session = new Session();
        $session->getFlashBag()->add(
            'devis-success',
            'Votre devis a bien été converti en projet'
        );

        return $this->redirectToRoute('projects_by_user',['id'=>$this->getUser()->getId()]);
    }

    /**
     * @Route("/new", name="devis_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $devi = new Devis();
        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($devi);
            $entityManager->flush();

            $session = new Session();
            $session->getFlashBag()->add(
                'devis-success',
                'Votre demande de devis a bien été envoyé'
            );
            return $this->redirectToRoute('app_register');
        }

        return $this->render('devis/new.html.twig', [
            'devi' => $devi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devis_show", methods={"GET"})
     */
    public function show(Devis $devi): Response
    {
        return $this->render('devis/show.html.twig', [
            'devi' => $devi,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="devis_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Devis $devi): Response
    {
        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('devis_index');
        }

        return $this->render('devis/edit.html.twig', [
            'devi' => $devi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devis_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Devis $devi): Response
    {
        if ($this->isCsrfTokenValid('delete' . $devi->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($devi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('devis_index');
    }
}
