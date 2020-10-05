<?php
declare(strict_types=1);

namespace Amocrmapi\Traits;

use Amocrmapi\Entity\Note;
use Amocrmapi\Entity\Task;

/**
 * Trait DefaultEntityTrait
 *
 * @package Amocrmapi\Traits
 */
trait DefaultEntityTrait
{
	/**
	 * @var array $entity = []
	 */
	private $entity = [];

	/**
     * Get value of custom field by id
     * 
     * @param int $id
     * 
     * @return mixed
     */
    public function getCustomField(int $id)
    {
        $ind = array_search($id, array_column($this->entity["custom_fields"], "id"));
        
        if ($ind === false) return false;

        return $this->entity["custom_fields"][$ind]["values"][0]["value"];
    }

    /**
     * Set entity custom field
     *
     * @param int $id
     * @param int | string $value
     * @param string $enum = null
     * @param string $subtype = null
     *
     * @return DefaultEntityTrait
     */
    public function setCustomField(int $id, $value, string $enum = null, string $subtype = null)
    {
        $ind = array_search($id, array_column($this->entity["custom_fields"], "id"));
        if ($ind !== false) {
            $this->entity["custom_fields"][$ind]["values"][0]["value"] = $value;

            if (!is_null($enum)) {
                $this->entity["custom_fields"][$ind]["values"][0]["enum"] = $enum;
            }

            if (!is_null($subtype)) {
                $this->entity["custom_fields"][$ind]["values"][0]["subtype"] = $subtype;
            }

            return $this;
        }

        $this->entity["custom_fields"][] = [
            "id" => $id,
            "values" => [[
                "value" => $value,
                "enum" => $enum,
                "subtype" => $subtype
            ]]
        ];

        return $this;
    }

    /**
     * Set custom field by enum
     * 
     * @param int $id
     * @param int $enum
     * 
     * @return DefaultEntityTraint
     */
    public function setCustomFieldByEnum(int $id, int $enum)
    {
        $customFieldValue = [
            "id" => $id,
            "values" => [
                [
                    "value" => $enum,
                ],
            ],
        ];

        $ind = array_search($id, array_column($this->entity["custom_fields"], "id"));

        if ($ind !== false) {
            $this->entity["custom_fields"][$ind] = $customFieldValue;
        } else {
            $this->entity["custom_fields"][] = $customFieldValue;
        }

        return $this;
    }

    /**
     * Add tag to entity
     *
     * @param string $tag
     *
     * @return DefaultEntityTrait
     */
    public function addTag(string $tag)
    {
        $this->entity["tags"] .= ",{$tag}";

        return $this;
    }

    /**
     * Remove tag from entity tags
     *
     * @param string $tag
     *
     * @return DefaultEntityTrait
     */
    public function removeTag(string $tag)
    {
        $tags = explode(',', $this->entity["tags"]);

        if (($key = array_search($tag, $tags)) !== false) {
            unset($tags[$key]);
        }

        $this->entity["tags"] = join(',', $tags);

        return $this;
    }

    /**
     * Add note to entity if need to save it on configuring entity
     * Have to flush notes with NoteApi after add or update current entity
     *
     * @param string $text
     * @param mixed $noteType
     *
     * @return DefaultEntityTrait
     */
    public function addNote(string $text, $noteType = 4)
    {
        $this->entity["notes"][] = (new Note)->parse([
            "text" => $text,
            "note_type" => $noteType,
            "element_type" => self::ELEMENT_TYPE
        ])->prepare();

        return $this;
    }

    /**
     * Get saved notes
     * 
     * @return array
     */
    public function getNotes() : array
    {
        $notes = [];
        foreach ($this->entity["notes"] as $note) {
            $notes[] = (new Note)->parse($note);
        }

        return $notes;
    }

    /**
     * Add note to entity if need to save it on configuring entity
     * Have to flush tasks with TaskApi after add or update current entity
     * 
     * @param string $text
     * @param int $responsibleUserId,
     * @param int $taskType = 1
     *
     * @return DefaultEntityTrait
     */
    public function addTask(string $text, int $responsibleUserId, int $taskType = 1)
    {
        $this->entity["tasks"][] = (new Task)->parse([
            "text" => $text,
            "task_type" => $taskType,
            "element_type" => self::ELEMENT_TYPE,
            "responsible_user_id" => $responsibleUserId
        ])->prepare();

        return $this;
    }

    /**
     * Get saved tasks
     * 
     * @return array
     */
    public function getTasks() : array
    {
        $tasks = [];
        foreach ($this->entity["tasks"] as $task) {
            $tasks[] = (new Task)->parse($task);
        }

        return $tasks;
    }

    /**
     * Return entity id
     * 
     * @return int|null
     */
    public function getId()
    {
        return $this->entity["id"];
    }

    /**
     * Set entity id
     * 
     * @param int $id
     *
     * @return DefaultEntityTrait
     */
    public function setId(int $id)
    {
        $this->entity["id"] = $id;

        return $this;
    }

    /**
     * Return entity name
     * 
     * @return string
     */
    public function getName() : string
    {
        return $this->entity["name"];
    }

    /**
     * Set entity name
     * 
     * @param string $name
     *
     * @return DefaultEntityTrait
     */
    public function setName(string $name)
    {
        $this->entity["name"] = $name;

        return $this;
    }

    /**
     * Return entity responsible_user_id
     * 
     * @return int|null
     */
    public function getResponsibleUserId()
    {
        return $this->entity["responsible_user_id"];
    }

    /**
     * Set entity responsible_user_id
     * 
     * @param int $responsibleUserId
     *
     * @return DefaultEntityTrait
     */
    public function setResponsibleUserId(int $responsibleUserId)
    {
        $this->entity["responsible_user_id"] = $responsibleUserId;

        return $this;
    }

    /**
     * Return entity created_by
     * 
     * @return int|null
     */
    public function getCreatedBy()
    {
        return $this->entity["created_by"];
    }

    /**
     * Set entity created_by
     * 
     * @param int $createdBy
     *
     * @return DefaultEntityTrait
     */
    public function setCreatedBy(int $createdBy)
    {
        $this->entity["created_by"] = $createdBy;

        return $this;
    }

    /**
     * Return entity created_at
     * 
     * @return int|null
     */
    public function getCreatedAt()
    {
        return $this->entity["created_at"];
    }

    /**
     * Set entity created_at
     * 
     * @param int $createdAt
     *
     * @return DefaultEntityTrait
     */
    public function setCreatedAt(int $createdAt)
    {
        $this->entity["created_at"] = $createdAt;

        return $this;
    }

    /**
     * Return entity updated_at
     * 
     * @return int|null
     */
    public function getUpdatedAt()
    {
        return $this->entity["updated_at"];
    }

    /**
     * Set entity updated_at
     * 
     * @param int $updatedAt
     *
     * @return DefaultEntityTrait
     */
    public function setUpdatedAt(int $updatedAt)
    {
        $this->entity["updated_at"] = $updatedAt;

        return $this;
    }

    /**
     * Return entity account_id
     * 
     * @return int|null
     */
    public function getAccountId()
    {
        return $this->entity["account_id"];
    }

    /**
     * Set entity tags
     * 
     * @param string $tags
     *
     * @return DefaultEntityTrait
     */
    public function setTags(string $tags)
    {
        $this->entity["tags"] = $tags;

        return $this;
    }

    /**
     * Return entity tags
     * 
     * @return string 
     */
    public function getTags() : string
    {
        return $this->entity["tags"];
    }

    /**
     * Return entity custom_fields
     * 
     * [
     *    id" => int,
     *   "name" => string,
     *   "values" => [
     *     "value" => string,
     *     "enum" => string,
     *     "subtype" => string
     *   ],
     *   "is_system" => bool
     * ]
     * 
     * @return array
     */
    public function getCustomFields() : array
    {
        return $this->entity["custom_fields"];
    }

    /**
     * Set entity custom_fields
     * 
     * [
     *    id" => int,
     *   "name" => string,
     *   "values" => [
     *     "value" => string,
     *     "enum" => string,
     *     "subtype" => string
     *   ],
     *   "is_system" => bool
     * ]
     * 
     * @param array $customFields
     *
     * @return DefaultEntityTrait
     */
    public function setCustomFields(array $customFields)
    {
        $this->entity["custom_fields"] = $customFields;

        return $this;
    }

    /**
     * Return entity closest_task_at
     * 
     * @return mixed
     */
    public function getClosestTaskAt()
    {
        return $this->entity["closest_task_at"];
    }
}