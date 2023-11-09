<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Utils\InvitationConstants;
use App\Entity\Invitation;
use App\Entity\User;

class InvitationsAPITest extends WebTestCase
{
    const API_PREFIX = '/api/v1/';

    public function testSendInvitationSuccess(): void
    {
        $client = static::createClient();

        $client->request('POST', $this::API_PREFIX . 'invitations', [
            'sender_id' => 2,
            'invited_id' => 3
        ]);

        // Assert the response status code
        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());

        // Assert the response content type
        $this->assertTrue(
            $client->getResponse()->headers->contains('Content-Type', 'application/json'),
            'the "Content-Type" header is "application/json"' 
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $responseData); // Assert that the response includes an invitation ID
        $this->assertArrayHasKey('status', $responseData); // Assert that the response includes invitation status
        $this->assertEquals(InvitationConstants::STATUS_PENDING, $responseData['status']); // Assert that the invitation status is pending

    }

    public function testAcceptInvitationSuccess(): void
    {
        $client = static::createClient();
        $container = self::$container;
        $entityManager = $container->get('doctrine.orm.default_entity_manager');

        $invitation = $this->_create_invitation($entityManager);
        $client->request('PATCH', $this::API_PREFIX . 'invitations/' . $invitation->getId() . '/accept');

        // Check the response
        $this->assertResponseIsSuccessful();

        // Assert the invitation status has been changed to 'accepted'
        $updatedInvitation = $entityManager->getRepository(Invitation::class)->find($invitation->getId());
        $this->assertEquals(InvitationConstants::STATUS_ACCEPTED, $updatedInvitation->getStatus());
    }

    private function _create_invitation($entityManager): Invitation
    {
        $sender = $entityManager->getReference(User::class, 2 );
        $invited = $entityManager->getReference(User::class, 3);
        // Create an Invitation entity
        $invitation = new Invitation();
        $invitation->setSender($sender);
        $invitation->setInvited($invited);
        $entityManager->persist($invitation);
        $entityManager->flush();
        return $invitation;
    }
}

