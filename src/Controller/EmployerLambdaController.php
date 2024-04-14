<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\Formation;
use App\Entity\Inscription;
use App\Entity\Produit;
use App\Form\InscriptionType;
use App\Repository\FormationRepository;
use App\Repository\InscriptionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class EmployerLambdaController extends AbstractController
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
        if ($userStatut == 1) {
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
    #[Route('/formation', name: 'app_employerlambda_index')]
    public function index(ManagerRegistry $doctrine,SessionInterface $session): Response
    {
        $redirectionVue='employer_lambda/index.html.twig';
        $response = $this->userAcces($session,$redirectionVue);
        if ($response !== null) {
            return $response;
        }

        $formations = $doctrine->getManager()->getRepository(Formation::class)->findAll();
        if(!$formations) {
            $message = "Pas de Formations a venir!";
            return $this->render($redirectionVue,[
            'message' =>$message]);
        }

        return $this->render($redirectionVue, array('ensFormation' => $formations));
    }

    #[Route('/formation/inscrire/{id}', name: 'app_employerlambda_inscription')]
    public function inscription(Request $request,ManagerRegistry $doctrine, $id,SessionInterface $session): Response
    {

        $userId = $session->get('id');
        $redirectionVue='employer_lambda/formulaireInscription.html.twig';
        $response = $this->userAcces($session,$redirectionVue);
        if ($response !== null) {
            return $response;
        }

        $formation = $doctrine->getManager()->getRepository(Formation::class)->find($id);

        if (!$formation) {
            $erreur = "Problème d'inscription veuillez re-éssayer. ";
            $redirection='/formation';
            return $this->render($redirectionVue, [
                'erreur'=>$erreur,
                'redirection'=>$redirection,
            ]);
        }

        $form = $this->createForm(InscriptionType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $employeRepository = $doctrine->getManager()->getRepository(Employe::class);
            $employe = $employeRepository->find($userId);
            $inscription = new Inscription();
            $inscription->setEmployer($employe);
            $inscription->setFormation($formation);
            $inscription->setStatut(1);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($inscription);
            $entityManager->flush();

            return $this->redirectToRoute('app_employerlambda_index');
        }

        return $this->render($redirectionVue, [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/mesformations', name: 'app_employerlambda_mesformations')]
    public function mesFormations(InscriptionRepository $inscriptionRepository,SessionInterface $session): Response
    {
        $userId = $session->get('id');
        $redirectionVue='employer_lambda/mesformations.html.twig';
        $response = $this->userAcces($session,$redirectionVue);
        if ($response !== null) {
            return $response;
        }

        $inscriptions = $inscriptionRepository->findBy(['employer' => $userId]);
        if (!$inscriptions){
            $message = "Vous n'avez pas de formation";
            return $this->render($redirectionVue, [
                'message'=>$message
                ]);
        }
        else{
            return $this->render($redirectionVue, [
                'inscriptions' => $inscriptions,
            ]);
        }
    }
}
