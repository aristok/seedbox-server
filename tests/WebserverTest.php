<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class WebserverTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRoot()
    {
        $this->get('/');

        $this->assertEquals(
            '{"success":false,"result":"resource was not found"}', $this->response->getContent()
        );
    }

    public function testGetAllServers()
    {
        $this->get('/api/v1/webservers', ['client'=>'badclientid']);
        $this->assertEquals(
            '{"success":false,"result":"Unauthorized action."}',
            $this->response->getContent()
        );

        $this->get('/api/v1/webservers', ['client'=>'3f1233b14d5457b263b443a8eaf253a0']);
        $this->assertEquals(
            '{"success":true,"result":[]}',
            $this->response->getContent()
        );
    }
}
