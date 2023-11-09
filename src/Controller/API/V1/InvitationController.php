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
use App\Utils\InvitationConstants;

class InvitationController extends AbstractController
{
    /**
     *
     * @var EventDispatcherInterface
     */
    protected $EntityManagerInterface;
    /**
     *
     * @var Registry
     */
    protected $workflowRegistry;

    public function __construct(
        EntityManagerInterface $entityManager,
        Registry $workflowRegistry)    
    {
        $this->entityManager = $entityManager;
        $this->workflowRegistry = $workflowRegistry;
    }
    public function sendInvitation(Request $request, SerializerInterface $serializer): Response
    {
        $invitation = new Invitation();
        
        $senderReference = $this->entityManager->getReference(
                                User::class, $request->request->get('sender_id')
                        );
        $invitedReference = $this->entityManager->getReference(
                                User::class, $request->request->get('invited_id')
                        );
        
        $invitation->setSender($senderReference);
        $invitation->setInvited($invitedReference);

        $this->entityManager->persist($invitation);
        $this->entityManager->flush();

        $jsonContent = $serializer->serialize($invitation, 'json', [
            'groups' => 'invitation_create',
        ]);
        return new Response($jsonContent, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    public function acceptInvitation(Request $request, Invitation $invitation): Response
    {
        $workflow = $this->workflowRegistry->get($invitation, 'invitation');

        if ($workflow->can($invitation, 'accept')) {
            $workflow->apply($invitation, 'accept');
            $this->entityManager->persist($invitation);
            $this->entityManager->flush();
            return new Response(InvitationConstants::ACCEPT_SUCCESS_MSG, Response::HTTP_OK);
        }
        return new Response(InvitationConstants::ACCEPT_ERROR_MSG, Response::HTTP_BAD_REQUEST);
    }

}
