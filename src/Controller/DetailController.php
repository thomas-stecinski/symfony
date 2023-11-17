<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class DetailController extends AbstractController
{
    #[Route('/detail/{id}', name: 'app_detail')]
    public function index(ManagerRegistry $doctrine, $id): Response
    {
        $contact = $doctrine->getRepository(Contact::class)->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Contact non trouvé');
        }

        return $this->render('detail/index.html.twig', [
            'contact' => $contact,
        ]);
    }

    #[Route('/delete-contact/{id}', name: 'app_delete_contact')]
    public function deleteContact(ManagerRegistry $doctrine, $id): Response
    {
        $entityManager = $doctrine->getManager();
        $contact = $entityManager->getRepository(Contact::class)->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Contact non trouvé');
        }

        $entityManager->remove($contact);
        $entityManager->flush();

        // Redirige vers la liste des contacts après la suppression
        return $this->redirectToRoute('app_contact');
    }

    #[Route('/add_contact/{id}', name: 'add_contact')]
    public function createContact(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit-contact/{id}', name: 'app_edit_contact')]
    public function editContact(ManagerRegistry $doctrine, $id, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $contact = $entityManager->getRepository(Contact::class)->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Contact non trouvé');
        }

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
