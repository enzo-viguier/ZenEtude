<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\LoginFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccessController extends AbstractController
{
   #[Route('/connexion', name: 'app_login')]
   public function login(Request $request): Response
   {
       $form = $this->createForm(LoginFormType::class);
       $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $formData = $form->getData();
        $email = $formData['email'];
        $password = $form->get('plainPassword')->getData(); // Assurez-vous que c'est le bon nom de champ
            $file = $this->getParameter('kernel.project_dir') . '\src\Data\users.json';

               if (file_exists($file)) {
                   $users = json_decode(file_get_contents($file), true);

                   foreach ($users as $user) {
                       if ($user['email'] === $email && $user['password'] === $password) {
                            $session = $request->getSession();
                            $session->set('username', $user['firstname']);

                           return $this->redirectToRoute('app_home');
                       }
                   }
               }

               $this->addFlash('error', 'Identifiants incorrects.');
       }


       return $this->render('security/login.html.twig', [
       'loginForm' => $form->createView(),
       ]);

   }

    #[Route('/inscription', name: 'app_inscription')]
    public function register(Request $request): Response
    {
        $formData = [];

        $form = $this->createForm(RegistrationFormType::class, $formData);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $data = [
                'lastname' => $formData['lastname'],
                'firstname' => $formData['firstname'],
                'email' => $formData['email'],
                'password' => $form->get('plainPassword')->getData(),
            ];

            $file = $this->getParameter('kernel.project_dir') . '\src\Data\users.json';

            $existingData = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
            $existingData[] = $data;

            file_put_contents($file, json_encode($existingData, JSON_PRETTY_PRINT));

            return $this->redirectToRoute('app_login');
        }

        return $this->render('access/inscription.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

}
