<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LoginController extends AbstractController
{

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/login', name: 'app_login')]
    public function index(Request $request, ManagerRegistry $doctrine, SessionInterface $session): Response
    {

        $form = $this->createForm(EmployeType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $userRepository = $doctrine->getManager()->getRepository(Employe::class);

            $user = $userRepository->findOneBy(['login' => $data->getLogin()]);

            if ($user && $user->getMdp() == (MD5($data->getMdp().'15'))) {
                $session->set('id', $user->getId());
                $session->set('statut', $user->getStatut());
                $this->logger->info(
                    PHP_EOL . PHP_EOL . PHP_EOL . " ------------------------------" .
                    PHP_EOL . PHP_EOL . " Connexion à la partie lambda réussie ", [
                    'user_id' => $user->getId(),
                    'login' => $user->getLogin(),
                    'nom' => $user->getNom(),
                    'prenom' => $user->getPrenom(),
                    'statut' => $user->getStatut(),
                    PHP_EOL . PHP_EOL . " ------------------------------" . PHP_EOL . PHP_EOL . PHP_EOL
                ]);

                return $this->redirectToRoute('app_home');
            } else {
                $message="Mail ou mode passe incorrect";
                $this->logger->error('Tentative de connexion échouée', [
                    'login' => $data->getLogin()
                ]);
                return $this->render('login/index.html.twig',[
                    'message' => $message]);
            }
        }

        return $this->render('login/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/logout', name: 'app_logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->clear();
        return $this->redirectToRoute('app_home');
    }

}