<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Contact;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class ContactController extends AbstractController
{
    #[Route('/', name: 'app_contact')]
    public function index(ManagerRegistry $contact): Response
    {

        $contact = $contact->getRepository(Contact::class)->findAll();

        return $this->render('contact/index.html.twig', [
            'contact' => $contact,
        ]);
    }
}
