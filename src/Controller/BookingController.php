<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookingController extends AbstractController
{
    #[Route('/booking/new', name: 'booking_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistre la réservation en base de données
            $entityManager->persist($booking);
            $entityManager->flush();

            $this->addFlash('success', 'Votre réservation a été enregistrée avec succès.');

            return $this->redirectToRoute('booking_success');
        }

        return $this->render('booking/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/booking/success', name: 'booking_success')]
    public function success(): Response
    {
        return $this->render('booking/success.html.twig');
    }
}
