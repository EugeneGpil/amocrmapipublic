<?php

use PHPUnit\Framework\TestCase;

use Amocrmapi\V2\API;

class CreateContactTest extends TestCase
{
    /**
     * @dataProvider apiProvider
     */
    public function testCreateEmptyContact($api)
    {
        $contact = $api->create("contact");

        $this->assertNull($contact->getId());
        
        sleep(1);
        $contactId = $api->get("contactapi")->add([$contact])[0]["id"];

        $this->assertInternalType("int", $contactId);
    }

    /**
     * @dataProvider apiProvider
     */
    public function testCreateContactWithParamsAndNote($api)
    {
        $contact = $api->create("contact");

        sleep(1);
        $accountInfo = $api->get("accountapi")->getAccountInfo();
        $userId = array_shift($accountInfo["users"])["id"];
        
        $keys = array_keys($accountInfo["custom_fields"]["contacts"]);
        $firstFieldId = $accountInfo["custom_fields"]["contacts"][$keys[8]]["id"];

        $secondFieldId = $accountInfo["custom_fields"]["contacts"][$keys[9]]["id"];
        
        $contact
            ->setResponsibleUserId($userId)
            ->setCustomField($firstFieldId, 1234)
            ->setCustomField($secondFieldId, 5678)
            ->addNote("note text")
        ;

        sleep(1);
        $contactId = $api->get("contactapi")->add([$contact])[0]["id"];

        $notes = $contact->getNotes();

        foreach ($notes as &$note) {
            $note->setElementId($contactId);
        }

        sleep(1);
        $addNoteResult = $api->get("noteapi")->add($notes);
        sleep(1);
        $contact = $api->get("contactapi")->get(["id" => $contactId])[0];

        $this->assertInternalType("int", $addNoteResult[0]["id"]);
        $this->assertInternalType("int", $contactId);
        $this->assertEquals(1234, $contact->getCustomField($firstFieldId));
        $this->assertEquals(5678, $contact->getCustomField($secondFieldId));
    }

    /**
     * @dataProvider apiProvider
     */
    public function testCreateContactWithMultipleEmailsAndPhones($api)
    {
        $contact = $api->create("contact");

        sleep(1);
        $accountInfo = $api->get("accountapi")->getAccountInfo();
        $contactsCF = $accountInfo["custom_fields"]["contacts"];

        $contact
            ->addEmail($contactsCF, "asdf@asdf.asdf")
            ->addEmail($contactsCF, "111@asdf.111")
            ->addPhone($contactsCF, "+77777", "OTHER")
            ->addPhone($contactsCF, "+3333", "MOB")
            ->addPhone($contactsCF, "+11111", "FAX")
        ;

        sleep(1);
        $contactId = $api->get("contactapi")->add([$contact])[0]["id"];

        sleep(1);
        $contact = $api->get("contactapi")->get(["id" => $contactId])[0];

        $phones  = $contact->getPhones($contactsCF);

        $this->assertEquals("111@asdf.111", $contact->getEmails($contactsCF)[1]["value"]);
        $this->assertTrue(in_array("+11111", array_column($phones, "value")));
        $this->assertTrue(in_array("+3333", array_column($phones, "value")));
        $this->assertTrue(in_array("+77777", array_column($phones, "value")));

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