<?php

use PHPUnit\Framework\TestCase;

use Amocrmapi\V2\API;

class CreateLeadTest extends TestCase
{
    /**
     * @dataProvider apiProvider
     */
    public function testCreateEmptyLead($api)
    {
        $lead = $api->create("lead");

        $this->assertNull($lead->getId());
        
        sleep(1);
        $leadId = $api->get("leadapi")->add([$lead])[0]["id"];

        $this->assertInternalType("int", $leadId);
    }

    /**
     * @dataProvider apiProvider
     */
    public function testCreateLeadWithParamsAndNote($api)
    {
        $lead = $api->create("lead");

        sleep(1);
        $accountInfo = $api->get("accountapi")->getAccountInfo();
        $userId = array_shift($accountInfo["users"])["id"];
        
        $keys = array_keys($accountInfo["custom_fields"]["leads"]);
        $firstFieldId = $accountInfo["custom_fields"]["leads"][$keys[7]]["id"];

        $secondFieldId = $accountInfo["custom_fields"]["leads"][$keys[8]]["id"];
        
        $lead
            ->setSale(111)
            ->setResponsibleUserId($userId)
            ->setCustomField($firstFieldId, 1234)
            ->setCustomField($secondFieldId, 5678)
            ->addNote("note text")
        ;

        sleep(1);
        $leadId = $api->get("leadapi")->add([$lead])[0]["id"];

        $notes = $lead->getNotes();

        foreach ($notes as &$note) {
            $note->setElementId($leadId);
        }

        sleep(1);
        $addNoteResult = $api->get("noteapi")->add($notes);

        sleep(1);
        $lead = $api->get("leadapi")->get(["id" => $leadId])[0];

        $this->assertInternalType("int", $addNoteResult[0]["id"]);
        $this->assertInternalType("int", $leadId);
        $this->assertEquals(1234, $lead->getCustomField($firstFieldId));
        $this->assertEquals(5678, $lead->getCustomField($secondFieldId));
    }

    /**
     * @dataProvider apiProvider
     */
    public function testCreateLeadWithParamsAndTask($api)
    {
        $lead = $api->create("lead");

        sleep(1);
        $accountInfo = $api->get("accountapi")->getAccountInfo();
        $userId = array_shift($accountInfo["users"])["id"];
        
        $keys = array_keys($accountInfo["custom_fields"]["leads"]);
        $firstFieldId = $accountInfo["custom_fields"]["leads"][$keys[7]]["id"];

        $secondFieldId = $accountInfo["custom_fields"]["leads"][$keys[8]]["id"];
        
        $lead
            ->setSale(111)
            ->setResponsibleUserId($userId)
            ->setCustomField($firstFieldId, 1234)
            ->setCustomField($secondFieldId, 5678)
            ->addTask("task text", $userId)
        ;

        sleep(1);
        $leadId = $api->get("leadapi")->add([$lead])[0]["id"];

        $tasks = $lead->getTasks();

        foreach ($tasks as &$task) {
            $task->setElementId($leadId);
        }

        sleep(1);
        $addTaskResult = $api->get("taskapi")->add($tasks);

        sleep(1);
        $lead = $api->get("leadapi")->get(["id" => $leadId])[0];

        $this->assertInternalType("int", $addTaskResult[0]["id"]);
        $this->assertInternalType("int", $leadId);
        $this->assertEquals(1234, $lead->getCustomField($firstFieldId));
        $this->assertEquals(5678, $lead->getCustomField($secondFieldId));
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