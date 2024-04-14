<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\Inscription;
use App\Entity\Produit;
use App\Form\EmployeType;
use App\Form\RechercheProduitType;
use App\Form\RechercheType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RechercheController extends AbstractController
{

    #[Route('/recherche', name: 'app_recherche')]
    public function rechercheFindBy(ManagerRegistry $doctrine)
    {
        $empoyes = $doctrine->getRepository(Employe::class)->findBy(['nom'=>'Castaing','statut'=>'0']);

        return $this->render('recherche/employes.html.twig',
            array('ensEmployes' => $empoyes)
        );
    }


    #[Route('/inscriptionrecherche', name: 'app_recherche_rechinscription')]
    public function rechInscription(ManagerRegistry $doctrine, Request $request)
    {
        $form = $this->createForm(RechercheType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employer = $form->getData();

            $nom = $employer['nom'];
            $prenom = $employer['prenom'];

            $Inscription = $doctrine->getRepository(Inscription::class)->rechlnscriptionsEmploye($nom, $prenom);
            return $this->render('recherche/employesInscrip.html.twig', [
                'form' => $form->createView(),
                'employesInscription' => $Inscription
            ]);
        }

        return $this->render('recherche/employesInscrip.html.twig', [
            'form' => $form->createView(),
            'employesInscription' => []
        ]);
    }



    #[Route('/inscriptionrecherchebyproduit', name: 'app_recherche_rechinscription_produit')]
    public function rechInscriptionByProduit(ManagerRegistry $doctrine, Request $request)
    {
        $form = $this->createForm(RechercheProduitType::class);
        $form->handleRequest($request);
        $produits = $doctrine->getRepository(Produit::class)->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $form->getData();

            $libelle = $produit['libelle'];
            $inscriptionsProduit = $doctrine->getRepository(Inscription::class)->rechInscriptionsProduit($libelle);
            return $this->render('recherche/employesInscripProduit.html.twig', [
                'employesInscription' => $inscriptionsProduit,
                'produits' => $produits,
                'form' => $form->createView(),
            ]);
        }
        return $this->render('recherche/employesInscripProduit.html.twig', [
            'employesInscription' => [],
            'produits' => $produits,
            'form' => $form->createView(),
        ]);
    }

}