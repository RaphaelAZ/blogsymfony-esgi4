<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Service;
use App\Entity\User;
use App\Form\BookingType;
use App\Form\UserType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class BookingController extends AbstractController
{
    #[Route('/booking/new', name: 'booking_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $booking = new Booking();
        $formBooking = $this->createForm(BookingType::class, $booking);
        
        $formBooking->handleRequest($request);
        if ($formBooking->isSubmitted() && $formBooking->isValid()) {
            $serviceId = $formBooking->get('service')->getData()->getId();
            $service = $entityManager->getRepository(Service::class)->findByID($serviceId);

            if (!$service) {
                throw new \Exception("Service {$serviceId} introuvable.");
            }

            $booking->setService($service);

            $session->set('booking', $booking);

            $user = new User();
            $formUser = $this->createForm(UserType::class, $user);

            return $this->render('booking/user_info.html.twig', [
                'formUser' => $formUser->createView()
            ]);
        }
    
        return $this->render('booking/new.html.twig', [
            'form' => $formBooking->createView(),
        ]);
    }


    #[Route('/booking/user', name: 'booking_user', methods: ['POST'])]
    public function saveUser(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $user = new User();
        $formUser = $this->createForm(UserType::class, $user);
        $formUser->handleRequest($request);
    
        if ($formUser->isSubmitted() && $formUser->isValid()) {
            $entityManager->persist($user);
    
            $booking = $session->get('booking');
    
            if ($booking) {
                $booking->setUser($user);
                $serv = $entityManager->getRepository(Service::class)->findByID($booking->getService()->getId()); // Réinsertion du service (Bugfix)
                $booking->setService($serv);

                $entityManager->persist($booking);
                $entityManager->flush();
    
                return $this->redirectToRoute('booking_success');
            }
        }
    
        return $this->render('booking/user_info.html.twig', [
            'formUser' => $formUser->createView(),
        ]);
    }

    #[Route('/booking/success', name: 'booking_success')]
    public function success(): Response
    {
        return $this->render('booking/success.html.twig');
    }
}
