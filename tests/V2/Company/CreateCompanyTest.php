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

        $this->assertNull($company->getId());

        sleep(1);
        $companyId = $api->get("companyapi")->add([$company])[0]["id"];

        $this->assertInternalType("int", $companyId);
    }

    /**
     * @dataProvider apiProvider
     */
    public function testCreateCompanyWithParamsAndNote($api)
    {
        $company = $api->create("company");

        sleep(1);
        $accountInfo = $api->get("accountapi")->getAccountInfo();
        $userId = array_shift($accountInfo["users"])["id"];

        $keys = array_keys($accountInfo["custom_fields"]["companies"]);
        $firstFieldId = $accountInfo["custom_fields"]["companies"][$keys[3]]["id"];

        $secondFieldId = $accountInfo["custom_fields"]["companies"][$keys[5]]["id"];

        $company
            ->setResponsibleUserId($userId)
            ->setCustomField($firstFieldId, 1234)
            ->setCustomField($secondFieldId, 5678)
            ->addNote("note text")
        ;

        sleep(1);
        $companyId = $api->get("companyapi")->add([$company])[0]["id"];

        $notes = $company->getNotes();

        foreach ($notes as &$note) {
            $note->setElementId($companyId);
        }

        sleep(1);
        $addNoteResult = $api->get("noteapi")->add($notes);

        sleep(1);
        $company = $api->get("companyapi")->get(["id" => $companyId])[0];

        $this->assertInternalType("int", $addNoteResult[0]["id"]);
        $this->assertInternalType("int", $companyId);
        $this->assertEquals(1234, $company->getCustomField($firstFieldId));
        $this->assertEquals(5678, $company->getCustomField($secondFieldId));
    }

    /**
     * @dataProvider apiProvider
     */
    public function testCreateCompanyWithParamsAndTask($api)
    {
        $company = $api->create("company");

        sleep(1);
        $accountInfo = $api->get("accountapi")->getAccountInfo();
        $userId = array_shift($accountInfo["users"])["id"];
        
        $keys = array_keys($accountInfo["custom_fields"]["companies"]);
        $firstFieldId = $accountInfo["custom_fields"]["companies"][$keys[3]]["id"];

        $secondFieldId = $accountInfo["custom_fields"]["companies"][$keys[5]]["id"];
        
        $company
            ->setResponsibleUserId($userId)
            ->setCustomField($firstFieldId, 1234)
            ->setCustomField($secondFieldId, 5678)
            ->addTask("task text", $userId)
        ;

        sleep(1);
        $companyId = $api->get("companyapi")->add([$company])[0]["id"];

        $tasks = $company->getTasks();

        foreach ($tasks as &$task) {
            $task->setElementId($companyId);
        }

        sleep(1);
        $addTaskResult = $api->get("taskapi")->add($tasks);

        sleep(1);
        $company = $api->get("companyapi")->get(["id" => $companyId])[0];

        $this->assertInternalType("int", $addTaskResult[0]["id"]);
        $this->assertInternalType("int", $companyId);
        $this->assertEquals(1234, $company->getCustomField($firstFieldId));
        $this->assertEquals(5678, $company->getCustomField($secondFieldId));
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