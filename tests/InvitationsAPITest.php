<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

use App\Utils\InvitationConstants;

class InvitationsAPITest extends WebTestCase
{
    public function testSendInvitation(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/v1/invitations', [
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
}

