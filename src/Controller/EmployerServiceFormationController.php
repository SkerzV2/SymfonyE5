<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\Formation;
use App\Entity\Inscription;
use App\Entity\Produit;
use App\Form\FormationType;
use App\Form\InscriptionType;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class EmployerServiceFormationController extends AbstractController
{
    private function userAcces(SessionInterface $session,String $redirectionVue): ?Response
    {
        $userStatut = $session->get('statut');

        if ($userStatut === null) {
            $erreur = "Veuillez vous connecter pour accéder à cette page." .
                " \n Redirection vers la page de connexion";

            $redirection = '/login';
            return $this->render($redirectionVue, [
                'erreur' => $erreur,
                'redirection' => $redirection,
            ]);
        }
        if ($userStatut == 0) {
            $erreur = "Vous n'avez pas accès à cette page" .
                " \n Redirection vers la page d'accueil";

            $redirection = '/home';
            return $this->render($redirectionVue, [
                'erreur' => $erreur,
                'redirection' => $redirection,
            ]);
        }
            return null;


    }

    #[Route('/gestionformation', name: 'app_employerserviceformation_gestionformation')]
    public function gestionFormation(SessionInterface $session, ManagerRegistry $doctrine): Response
    {
        $redirectionVue='employer_service_formation/gestionFormation.html.twig';
        $response = $this->userAcces($session,$redirectionVue);
        if ($response !== null) {
            return $response;
        }

        $formations = $doctrine->getManager()->getRepository(Formation::class)->findAll();
        if(!$formations) {
            $message = "Pas de Formations a venir! veillez en crée une!";
            return $this->render($redirectionVue, [
                'message' => $message
            ]);
        }
        return $this->render($redirectionVue, array('ensFormation' => $formations));
    }
    #[Route('/gestionformation/ajouter', name: 'app_employerserviceformation_ajouterformation')]
    public function ajouterFormation(Request $request,ManagerRegistry $doctrine,SessionInterface $session): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $formation->setNombreInscrit(+1);
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('employer_service_formation/formulaireAjouterFormations.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/gestionformation/supprimer/{id}', name: 'app_employerserviceformation_supprimerformation')]
    public function supprimerFormation($id, FormationRepository $formationRepository, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        $redirectionVue='employer_service_formation/gestionFormation.html.twig';
        $response = $this->userAcces($session,$redirectionVue);
        if ($response !== null) {
            return $response;
        }

        $formation = $formationRepository->find($id);
        if (!$formation){
            $message = $redirectionVue;
            return $this->render($redirectionVue, [
                'message' => $message]);
        }
        $hasInscriptions = $formationRepository->hasInscriptions($id);

        if ($hasInscriptions) {
            $message = "La formation ne peut pas être supprimer.\n
           "."elle contient deja des inscriptions";

            return $this->render($redirectionVue, [
                'message' => $message]);
        }
        else {
            $entityManager->remove($formation);
            $entityManager->flush();
            return $this->redirectToRoute('app_employerserviceformation_gestionformation');
        }
    }
    #[Route('/gestioninscription', name: 'app_employerserviceformation_gestioninscriptionformation')]
    public function gestionInscriptionFormation(ManagerRegistry $doctrine,SessionInterface $session): Response
    {
        $redirectionVue='employer_service_formation/gestionInscription.html.twig';
        $response = $this->userAcces($session,$redirectionVue);
        if ($response !== null) {
            return $response;
        }
        $Inscriptions = $doctrine->getManager()->getRepository(Inscription::class)->findBy(['statut' => 1]);
        if(!$Inscriptions)
        {
            $message = "Il n'y a pas d'inscriptions en cours";
            return $this->render($redirectionVue, [
                'message' => $message]);
        }
        return $this->render('employer_service_formation/gestionInscription.html.twig', array('ensInscription' => $Inscriptions));
    }

    #[Route('/gestioninscription/inscrire/{id}', name: 'app_employerserviceformation_validerinscriptionformation')]
    public function validerInscriptionFormation(ManagerRegistry $doctrine,SessionInterface $session,$id): Response
    {
        $redirectionVue='employer_service_formation/gestionInscription.html.twig';
        $response = $this->userAcces($session,$redirectionVue);
        if ($response !== null) {
            return $response;
        }

        $entityManager = $doctrine->getManager();
        $inscription = $entityManager->getRepository(Inscription::class)->findOneBy(['id' => $id]);

        if (!$inscription){
            $message = "La demande d'inscription n'existe pas";
            return $this->render($redirectionVue, [
                'message' => $message
            ]);
        }

        $inscription->setStatut(2);
        $entityManager->persist($inscription);
        $entityManager->flush();
        return $this->redirectToRoute('app_employerserviceformation_gestioninscriptionformation');
    }
    #[Route('/gestioninscription/refuser/{id}', name: 'app_employerserviceformation_refuserinscriptionformation')]
    public function refuserInscriptionFormation(ManagerRegistry $doctrine,SessionInterface $session,$id): Response
    {
        $redirectionVue='employer_service_formation/gestionInscription.html.twig';
        $response = $this->userAcces($session,$redirectionVue);
        if ($response !== null) {
            return $response;
        }

        $entityManager = $doctrine->getManager();
        $inscription = $entityManager->getRepository(Inscription::class)->findOneBy(['id' => $id]);
        if (!$inscription){
            $message = "La demande d'inscription n'existe pas";
            return $this->render('employer_service_formation/gestionInscription.html.twig', [
            'message' => $message
            ]);
        }
        $inscription->setStatut(0);
        $entityManager->persist($inscription);
        $entityManager->flush();
        return $this->redirectToRoute('app_employerserviceformation_gestioninscriptionformation');
    }

    #[Route('/ajoutProduitFormation', name:'app_employerserviceformation_ajoutproduitformation')]
    public function ajoutProduitFormation(ManagerRegistry $doctrine):void
    {
        $entityManager = $doctrine->getManager();
        $produit = new Produit();
        $produit->setLibelle('JavaScript');
        $formation = new Formation();
        $dateDebut = new \DateTime('2023-12-8');
        $formation->setDateDebut($dateDebut);
        $formation->setDepartement('77');
        $formation->setNbreHeures(22);
        $formation->setDescription("Pack Javascript");
        $formation->setLibelle("Formation JavaScript");
        $formation->setImage("https://fr-images.tuto.net/tuto/thumb/648/288/99441.jpg");
        $entityManager->persist($produit);
        $formation->setProduit($produit);
        $entityManager->persist($formation);
        $entityManager->flush();

    }
}
