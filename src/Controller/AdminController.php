<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\Offer;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/offers", name="offers")
     */
    public function offers(OfferRepository $offerRepository)
    {
        return $this->render('admin/offers.html.twig', [
            'offers' => $offerRepository->findBy(['processed' => false]),
        ]);
    }

    /**
     * @Route("/offersToProject/{offer}", name="offers_to_project")
     */
    public function offersToProject(Offer $offer, EntityManagerInterface $em)
    {
        $devis = new Devis();
        $devis->setIsProject(true);
        $devis->setName("PROJECT#" . $offer->getId());
        switch ($offer->getOfferNumber()) {
            case 1:
                $devis->setDescription("Offer type 1");
                $devis->setBudgetTo(500);
                $devis->setPrice(500);
                $devis->setNeeds("Un site web vitrine
                \nHébergement inclus
                \nDomaine au choix");
            break;
            case 2:
                $devis->setDescription("Offer type 2");
                $devis->setBudgetTo(1200);
                $devis->setPrice(1200);
                $devis->setNeeds("Site web professionnel avancé
                \nUn espace d'administration
                \nHébergement inclus
                \nDomaine au choix
                \nUne application mobile");
                break;
            case 3:
                $devis->setDescription("Offer type 3");
                $devis->setBudgetTo(2500);
                $devis->setPrice(2500);
                $devis->setNeeds("Avantages de l'offre pro
                \nUne application de bureau
                \nUn logo
                \nUne page facebook");
                break;
            default:
                throw new Exception("Offer type not between [1-3]");
                break;
        }
        $devis->setBudgetFrom(0);
        $devis->setEmail($offer->getEmail());
        $devis->setStatus(1);

        $offer->setProcessed(true);

        $em->persist($devis);
        $em->flush();

        $session = new Session();
        $session->getFlashBag()->add(
            'devis-success',
            'Votre devis a bien été converti en projet'
        );

        return $this->redirectToRoute('offers');
    }
}
