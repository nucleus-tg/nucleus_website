<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render('default/about.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('default/contact.html.twig');
    }

    /**
     * @Route("/faq", name="faq")
     */
    public function faq()
    {
        return $this->render('default/faq.html.twig');
    }

    /**
     * @Route("/sendemail", name="send_contact_mail")
     */
    public function sendEmail(\Swift_Mailer $mailer, Request $request)
    {
        $message = (new \Swift_Message($request->request->get('object')))
            ->setFrom($request->request->get('email'))
            ->setTo('nikaboue10@gmail.com')
            ->setBody(
                $request->request->get('message'),
                'text/html'
            );

        $mailer->send($message);

        return $this->render('default/contact.html.twig');
    }
}
