<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Event\ContactEvent;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

#[Route('/contact')]
class ContactController extends AbstractController
{
    protected $eventDisptacher;

    public function __construct( EventDispatcherInterface $eventDisptacher)
    {
        $this->eventDisptacher = $eventDisptacher;
    }
    #[Route('/', name: 'app_contact_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContactRepository $contactRepository): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactRepository->add($contact, true);
            $event = new ContactEvent($contact);
            $this->eventDisptacher->dispatch($event);


            return $this->redirectToRoute('app_contact_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contact/contact.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

}
