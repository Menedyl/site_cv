<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('home.html.twig', [
            'current_page' => 'home'
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, \Swift_Mailer $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = new \Swift_Message("Demande d'information: " . $form->get('name')->getData());

            $message
                ->setFrom($this->getParameter('app.mail.from'))
                ->setTo($this->getParameter('app.mail.to'))
                ->setBody($this->renderView('emails/contact.html.twig', [
                    'name' => $form->get('name')->getData(),
                    'content' => $form->get('content')->getData(),
                    'mail' => $form->get('mail')->getData()
                ]), 'text/html');

            $mailer->send($message);
            $this->addFlash('info', 'Votre message a bien été envoyé.');

            return $this->redirectToRoute('home');
        }
        return $this->render('contact.html.twig', [
            'form' => $form->createView(),
            'current_page' => 'contact'
        ]);
    }
}
