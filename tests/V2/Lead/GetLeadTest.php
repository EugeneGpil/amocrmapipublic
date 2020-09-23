<?php

use PHPUnit\Framework\TestCase;

use Amocrmapi\V2\API;

class GetLeadTest extends TestCase
{
    /**
     * @dataProvider apiProvider
     */
    public function testGetLead($api)
    {
        $lead = $api->create("lead");
        sleep(1);
        $leadId = $api->get("leadapi")->add([$lead])[0]["id"];
        sleep(1);
        $leadId = $api->get("leadapi")->get(["id" => $leadId])[0]->getId();

        $this->assertInternalType("int", $leadId);
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