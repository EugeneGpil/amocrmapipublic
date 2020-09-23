<?php
declare(strict_types=1);

namespace Amocrmapi\Entity;

use Amocrmapi\Dependencies\EntityInterface;

/**
 * Class Note
 *
 * @package Amocrmapi\Entity
 */
class Note implements EntityInterface
{
	/**
	 * @var array $entity = []
	 */
	private $entity;

    /**
     * Note constructor.
     */
    public function __construct()
    {
        $this->entity = [
            "id" => null,
            "responsible_user_id" => null,
            "created_by" => null,
            "created_at" => null,
            "updated_at" => null,
            "account_id" => null,
            "group_id" => null,
            "is_editable" => null,
            "element_id" => null,
            "element_type" => null,
            "attachment" => null,
            "note_type" => null,
            "text" => null
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
     * @return Note
     */
    public function parse(array $data)
    {
        foreach ($data as $ind => $val) {
            $this->entity[$ind] = $val;
        }

    	return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->entity["id"];
    }

    /**
     * @param $id
     *
     * @return Note
     */
    public function setId($id)
    {
        $this->entity["entity"]["id"] = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponsibleUserId()
    {
        return $this->entity["responsible_user_id"];
    }

    /**
     * @param $responsibleUserId
     *
     * @return Note
     */
    public function setResponsibleUserId($responsibleUserId)
    {
        $this->entity["responsible_user_id"] = $responsibleUserId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->entity["created_by"];
    }

    /**
     * @param $createdBy
     *
     * @return Note
     */
    public function setCreatedBy($createdBy)
    {
        $this->entity["created_by"] = $createdBy;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->entity["created_at"];
    }

    /**
     * @param $createdAt
     *
     * @return Note
     */
    public function setCreatedAt($createdAt)
    {
        $this->entity["created_at"] = $createdAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->entity["updated_at"];
    }

    /**
     * @param $updatedAt
     *
     * @return Note
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->entity["updated_at"] = $updatedAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountId()
    {
        return $this->entity["account_id"];
    }

    /**
     * @param $accountId
     *
     * @return Note
     */
    public function setAccountId($accountId)
    {
        $this->entity["account_id"] = $accountId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGroupId()
    {
        return $this->entity["group_id"];
    }

    /**
     * @param $groupId
     *
     * @return Note
     */
    public function setGroupId($groupId)
    {
        $this->entity["group_id"] = $groupId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsEditable()
    {
        return $this->entity["is_editable"];
    }

    /**
     * @param $isEditable
     *
     * @return Note
     */
    public function setIsEditable($isEditable)
    {
        $this->entity["is_editable"] = $isEditable;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getElementId()
    {
        return $this->entity["element_id"];
    }

    /**
     * @param $elementId
     *
     * @return Note
     */
    public function setElementId($elementId)
    {
        $this->entity["element_id"] = $elementId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getElementType()
    {
        return $this->entity["element_type"];
    }

    /**
     * @param $elementType
     *
     * @return Note
     */
    public function setElementType($elementType)
    {
        switch ($elementType) {
            case 'contact':
            case '1':
                $this->entity["element_type"] = 1;
                break;

            case 'lead':
            case '2':
                $this->entity["element_type"] = 2;
                break;

            case 'company':
            case '3':
                $this->entity["element_type"] = 3;
                break;

            case 'task':
            case '4':
                $this->entity["element_type"] = 4;
                break;

            case 'customer':
            case '12':
                $this->entity["element_type"] = 12;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttachment()
    {
        return $this->entity["attachment"];
    }

    /**
     * @param $attachment
     *
     * @return Note
     */
    public function setAttachment($attachment)
    {
        $this->entity["attachment"] = $attachment;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNoteType()
    {
        return $this->entity["note_type"];
    }

    /**
     * @param string $noteType
     *
     * @return Note
     */
    public function setNoteType(string $noteType)
    {
        switch ($noteType) {
            case 'DEAL_CREATED':
            case 'lead created':
            case '1':
                $this->entity["note_type"] = 1;
                break;

            case 'CONTACT_CREATED':
            case 'contact created':
            case '2':
                $this->entity["note_type"] = 2;
                break;

            case 'DEAL_STATUS_CHANGED':
            case 'pipeline change':
            case '3':
                $this->entity["note_type"] = 3;
                break;

            case 'COMMON':
            case 'default':
            case '4':
                $this->entity["note_type"] = 4;
                break;

            case 'CALL_IN':
            case 'call in':
            case '10':
                $this->entity["note_type"] = 10;
                break;

            case 'CALL_OUT':
            case 'call out':
            case '11':
                $this->entity["note_type"] = 11;
                break;

            case 'COMPANY_CREATED':
            case 'company created':
            case '12':
                $this->entity["note_type"] = 12;
                break;

            case 'TASK_RESULT':
            case 'task result':
            case '13':
                $this->entity["note_type"] = 12;
                break;

            case 'SYSTEM':
            case 'system':
            case 'system message':
            case '25':
                $this->entity["note_type"] = 25;
                break;

            case 'SMS_IN':
            case 'sms in':
            case '102':
                $this->entity["note_type"] = 102;
                break;

            case 'SMS_OUT':
            case 'sms out':
            case '103':
                $this->entity["note_type"] = 103;
                break;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->entity["text"];
    }

    /**
     * @param $text
     *
     * @return Note
     */
    public function setText($text)
    {
        $this->entity["text"] = $text;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLinks()
    {
        return $this->entity["links"];
    }
}