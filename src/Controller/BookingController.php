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
            $user = $this->getUser();
            if ($user) {
                $booking->setUser($user);
            }

            $date = $form->get('date')->getData();
            $heure = $form->get('heure')->getData();

            // Creation datetime
            $dateTime = new \DateTime();
            $dateTime->setDate($date->format('Y'), $date->format('m'), $date->format('d'));
            $dateTime->setTime($heure->format('H'), $heure->format('i'));

            $booking->setDate($dateTime);

            $entityManager->persist($booking);
            $entityManager->flush();

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
