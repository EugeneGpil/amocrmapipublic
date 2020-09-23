<?php

use PHPUnit\Framework\TestCase;

use Amocrmapi\V2\API;

class GetCompanyTest extends TestCase
{
    /**
     * @dataProvider apiProvider
     */
    public function testGetCompany($api)
    {
        $company = $api->create("company");
        sleep(1);
        $companyId = $api->get("companyapi")->add([$company])[0]["id"];
        sleep(1);
        
        $companyId = $api->get("companyapi")->get(["id" => $companyId])[0]->getId();

        $this->assertInternalType("int", $companyId);
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