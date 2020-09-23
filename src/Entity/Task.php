<?php
declare(strict_types=1);

namespace Amocrmapi\Entity;

use Amocrmapi\Dependencies\EntityInterface;

class Task implements EntityInterface
{
	/**
	 * @var array $entity = []
	 */
	private $entity;

    public function __construct()
    {
        $this->entity = [
            "id" => null,
		    "element_id" => null,
		    "element_type" => null,
		    "complete_till_at" => null,
		    "task_type" => null,
		    "text" => null,
		    "created_at" => null,
		    "updated_at" => null,
		    "responsible_user_id" => null,
		    "is_completed" => null,
		    "created_by" => null,
		    "account_id" => null,
		    "group_id" => null,
		    "result" => null,
		    "links" => null
        ];
    }

	/**
     * Prepare entity to sync with amocrm
     * 
     * @return array
     */
    public function prepare() : array
    {
        return $this->entity;
    }

    /**
     * Parse lead entity from amocrm response
     * 
     * @param array @data
     * 
     * @return \Amocrmapi\Entity\Task
     */
    public function parse(array $data) : \Amocrmapi\Entity\Task
    {
        foreach ($data as $ind => $val) {
            $this->entity[$ind] = $val;
        }

    	return $this;
    }

    public function setTypeOfTask(string $type)
    {
        switch ($type) {
            case 'call':
                $this->entity["task_type"] = 1;
                break;

            case 'meet':
                $this->entity["task_type"] = 2;
                break;

            case 'letter':
            case 'email':
            case 'send':
                $this->entity["task_type"] = 3;
                break;
        }

        return $this;
    }

    public function setTaskElementType(string $type)
    {
        switch ($type) {
            case 'contact':
                $this->entity["element_type"] = 1;
                break;

            case 'lead':
                $this->entity["element_type"] = 2;
                break;

            case 'company':
                $this->entity["element_type"] = 3;
                break;

            case 'customer':
                $this->entity["element_type"] = 12;
                break;
        }

        return $this;
    }

    public function getId()
    {
        return $this->entity["id"];
    }

    public function setId($id)
    {
        $this->entity["id"] = $id;

        return $this;
    }

    public function getElementId()
    {
        return $this->entity["element_id"];
    }

    public function setElementId($elementId)
    {
        $this->entity["element_id"] = $elementId;

        return $this;
    }

    public function getElementType()
    {
        return $this->entity["element_type"];
    }

    public function setElementType($elementType)
    {
        $this->entity["element_type"] = $elementType;

        return $this;
    }

    public function getCompleteTillAt()
    {
        return $this->entity["complete_till_at"];
    }

    public function setCompleteTillAt($completeTillAt)
    {
        $this->entity["complete_till_at"] = $completeTillAt;

        return $this;
    }

    public function getTaskType()
    {
        return $this->entity["task_type"];
    }

    public function setTaskType($taskType)
    {
        $this->entity["task_type"] = $taskType;

        return $this;
    }

    public function getText()
    {
        return $this->entity["text"];
    }

    public function setText($text)
    {
        $this->entity["text"] = $text;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->entity["created_at"];
    }

    public function setCreatedAt($createdAt)
    {
        $this->entity["created_at"] = $createdAt;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->entity["updated_at"];
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->entity["updated_at"] = $updatedAt;

        return $this;
    }

    public function getResponsibleUserId()
    {
        return $this->entity["responsible_user_id"];
    }

    public function setResponsibleUserId($responsibleUserId)
    {
        $this->entity["responsible_user_id"] = $responsibleUserId;

        return $this;
    }

    public function getIsCompleted()
    {
        return $this->entity["is_completed"];
    }

    public function setIsCompleted($isCompleted)
    {
        $this->entity["is_completed"] = $isCompleted;

        return $this;
    }

    public function getCreatedBy()
    {
        return $this->entity["created_by"];
    }

    public function setCreatedBy($createdBy)
    {
        $this->entity["created_by"] = $createdBy;

        return $this;
    }

    public function getAccountId()
    {
        return $this->entity["account_id"];
    }

    public function getGroupId()
    {
        return $this->entity["group_id"];
    }

    public function setGroupId($groupId)
    {
        $this->entity["group_id"] = $groupId;

        return $this;
    }

    public function getResult()
    {
        return $this->entity["result"];
    }

    public function setResult($result)
    {
        $this->entity["result"] = $result;

        return $this;
    }
}