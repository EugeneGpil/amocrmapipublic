<?php

use PHPUnit\Framework\TestCase;

use Amocrmapi\V2\API;

class GetContactTest extends TestCase
{
    /**
     * @dataProvider apiProvider
     */
    public function testGetContact($api)
    {
        $contact = $api->create("contact");
        sleep(1);
        $contactId = $api->get("contactapi")->add([$contact])[0]["id"];
        sleep(1);
        
        $contactId = $api->get("contactapi")->get(["id" => $contactId])[0]->getId();

        $this->assertInternalType("int", $contactId);
    }

    public function apiProvider()
    {
        $api = new API(
            'freshtest',
            'karasunokami@yahoo.com',
            '390537cea8f0fe70769bcdb6ef23f0a1'
        );
        $api->connect();
        return [[
            $api,
            null,
            null
        ]];
    }
}