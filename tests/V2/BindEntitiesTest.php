<?php

use PHPUnit\Framework\TestCase;

use Amocrmapi\V2\API;

class BindEntitiesTest extends TestCase
{
    /**
     * @dataProvider apiProvider
     */
    public function testCreateEntities($api)
    {
        $lead =     $api->create("lead");
        $contact =  $api->create("contact");
        $company =  $api->create("company");
        
        
        sleep(1);
        $companyId = $api->get("companyapi")->add([$company])[0]["id"];

        $lead->setCompany($companyId);

        sleep(1);
        $leadId = $api->get("leadapi")->add([$lead])[0]["id"];

        $contact->addLead($leadId)->setCompany($companyId);

        sleep(1);
        $contactId = $api->get("contactapi")->add([$contact])[0]["id"];


        sleep(1);
        $lead = $api->get("leadapi")->get(["id"=> $leadId])[0];
        $lead->addContact($contactId);

        sleep(1);
        $leadId = $api->get("leadapi")->update([$lead])[0]["id"];

        sleep(1);
        $lead = $api->get("leadapi")->get(["id"=> $leadId])[0];
        
        $this->assertEquals($contactId, $lead->getContacts()["id"][0]);
        $this->assertEquals($companyId, $lead->getCompany()["id"]);
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