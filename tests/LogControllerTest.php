<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LogControllerTest extends WebTestCase
{
    public function testCountAction()
    {
        // Create a client and make a request to the /logs/count endpoint
        $client = static::createClient();
        $client->request('GET', '/logs/count');

        // Assert that the response status code is 200 OK
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that the response is a JSON object
        $response = json_decode($client->getResponse()->getContent());
        $this->assertTrue(is_object($response));

        // Assert that the response has a 'count' field
        $this->assertObjectHasAttribute('count', $response);
    }
}
