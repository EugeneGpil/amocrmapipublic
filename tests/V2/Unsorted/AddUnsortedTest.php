<?php

use PHPUnit\Framework\TestCase;

use Amocrmapi\V2\API;

class AddUnsortedTest extends TestCase
{
    /**
     * @dataProvider apiProvider
     */
    public function testGetLead($api)
    {
        $unsorted = $api->create("unsorted");
        $lead = $api->create("lead");
        $contact = $api->create("contact");
        $company = $api->create("company");

        $cf = $api->get("accountapi")->getAccountInfo()["custom_fields"]["contacts"];

        $lead
            ->setName("Заявка с сайта")
            ->addTag("test, some")
            ->setCustomField(1974337, "asdfasdf")
            ->setCustomField(1971687, "Отрада")
            ->setSale(1971)
        ;
        $contact
            ->setName("Имя контакта")
            ->addTag("test, some")
            ->addPhone($cf, "+7111")
            ->addPhone($cf, "+7222", "HOME")
            ->addEmail($cf, "some@some.some", "PRIV")
            ->addEmail($cf, "asdf@asdf.asdf")
            ->setCustomField(1976059, "LG")
        ;
        $company
            ->setName("Имя компании")
            ->addTag("test, some")
            ->setCustomField(1964587, "родитель 1")
            ->setCustomField(1964585, "родитель 2")
            ->setCustomField(1973475, "first")
        ;

        $unsorted
            ->setLead($lead)
            ->setContact($contact)
            ->setCompany($company)
            ->addNote("some text")
        ;

        $unsorted->setFormPage("https://site.com");

        $resp = $api->get("unsortedapi")->add([$unsorted]);
        $this->assertEquals("success", $resp["status"]);
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