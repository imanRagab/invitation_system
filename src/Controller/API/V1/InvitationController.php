<?php

namespace App\Controller\API\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Workflow\Registry;
use App\Entity\User;
use App\Entity\Invitation;

class InvitationController extends AbstractController
{
    public function sendInvitation(
        Request $request, EntityManagerInterface $entityManager, 
        SerializerInterface $serializer): Response
    {
        $invitation = new Invitation();
        
        $senderReference = $entityManager->getReference(
                                User::class, $request->request->get('sender_id')
                        );
        $invitedReference = $entityManager->getReference(
                                User::class, $request->request->get('invited_id')
                        );
        
        $invitation->setSender($senderReference);
        $invitation->setInvited($invitedReference);

        $entityManager->persist($invitation);
        $entityManager->flush();

        $jsonContent = $serializer->serialize($invitation, 'json', [
            'groups' => 'invitation_create',
        ]);
        return new Response($jsonContent, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }
}
