<?php

use PHPUnit\Framework\TestCase;

use Amocrmapi\V2\API;

class APITest extends TestCase
{
    /**
     * @dataProvider apiProvider
     */
    public function testCreateEntities($api)
    {
        $lead =     $api->create("lead");
        $contact =  $api->create("contact");
        $company =  $api->create("company");
        $task =     $api->create("task");
        $note =     $api->create("note");
        $unsorted = $api->create("unsorted");

        $this->assertInstanceOf("\Amocrmapi\Entity\Lead",     $lead);
        $this->assertInstanceOf("\Amocrmapi\Entity\Contact",  $contact);
        $this->assertInstanceOf("\Amocrmapi\Entity\Company",  $company);
        $this->assertInstanceOf("\Amocrmapi\Entity\Task",     $task);
        $this->assertInstanceOf("\Amocrmapi\Entity\Note",     $note);
        $this->assertInstanceOf("\Amocrmapi\Entity\Unsorted", $unsorted);
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