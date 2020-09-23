<?php

use PHPUnit\Framework\TestCase;

use Amocrmapi\V2\API;

class CreateCompanyTest extends TestCase
{
    /**
     * @dataProvider apiProvider
     */
    public function testCreateEmptyCompany($api)
    {
        $company = $api->create("company");

        // $accountInfo = $api->get("accountapi")->getAccountInfo();
        // print_r($accountInfo["custom_fields"]["companies"]);die;

        $company
            ->setCustomField(1430712, "111111", "WORK")
            ->setCustomField(1430714, "some@e.com", "WORK")
            ->setCustomField(1992173, "раша", "WORK");

        sleep(1);
        $companyId = $api->get("companyapi")->add([$company])[0]["id"];

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