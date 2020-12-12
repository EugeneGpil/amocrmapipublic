<?php
declare(strict_types=1);

namespace Amocrmapi\Entity;

use Amocrmapi\Traits\DefaultEntityTrait;
use Amocrmapi\Dependencies\EntityInterface;

/**
 * Class Contact
 *
 * @package Amocrmapi\Entity
 */
class Contact implements EntityInterface
{
    use DefaultEntityTrait;

    /**
     * Default name for contact
     */
    const CONTACT_DEFAULT_NAME = "api contact";
    /**
     * Element type for note and task amoapi
     */
    const ELEMENT_TYPE = 1;

    /**
     * Contact constructor.
     */
    public function __construct()
    {
        $this->entity = [
            "id" => null,
            "group_id" => null,
            "account_id" => null,
            "created_at" => null,
            "updated_at" => null,
            "created_by" => null,
            "updated_by" => null,
            "closest_task_at" => null,
            "responsible_user_id" => null,
            "name" => self::CONTACT_DEFAULT_NAME,

            "tags" => "",
            "notes" => [],
            "tasks" => [],
            "custom_fields" => [],
            "leads" => ["id" => []],
            "company" => ["id" => []],
            "customers" => ["id" => []],

            "unlink" => null
        ];
    }
    
    /**
     * Prepare entity to sync with amocrm
     * 
     * @return array
     */
    public function prepare() : array
    {
        if (isset($this->entity["leads"]["id"])) {
            $this->entity["leads_id"] = $this->entity["leads"]["id"];
        }
        
        if ($this->entity["company"]) {
            $this->entity["company_id"] = $this->entity["company"]["id"];
        }

        return $this->entity;
    }

    /**
     * Parse lead entity from amocrm response
     * 
     * @param array @data
     *
     * @return Contact
     */
    public function parse(array $data)
    {
        $data["tags"] = join(',', array_reverse(array_column($data["tags"], "name")));
        $this->entity = $data;
        
        return $this;
    }

    /**
     * Set first contact phone
     *
     * @param array $customFields - all custom fields of contacts
     * of contact (AccountApi::getAccountInfo()["custom_fields"]["contacts"])
     * @param string $phone
     * @param string $enum = "WORK" - id of enum or one of
     * ["WORK", "WORKDD", "MOB", "FAX", "HOME", "OTHER"]
     *
     * @return Contact
     */
    public function setPhone(array $customFields, string $phone, string $enum = "WORK")
    {
        $id = $this->findPhoneId($customFields);
        $this->setCustomField($id, $phone, $enum);

        return $this;
    }

    /**
     * Get first name
     * 
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->entity["first_name"] ?? null;
    }

    /**
     * Get last name
     * 
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->entity["last_name"] ?? null;
    }

    /**
     * Set first name
     * 
     * @param string $firstName
     * @return Contact
     */
    public function setFirstName(string $firstName)
    {
        $this->entity["first_name"] = $firstName;
        return $this;
    }

    /**
     * Set last name
     * 
     * @param string $lastName
     * @return Contact
     */
    public function setLastName(string $lastName)
    {
        $this->entity["last_name"] = $lastName;
        return $this;
    }

    /**
     * Add phone to exist phone numbers
     *
     * @param array $customFields - all custom fields of contacts
     * of contact (AccountApi::getAccountInfo()["custom_fields"]["contacts"])
     * @param string $phone
     * @param string $enum = "WORK" - id of enum or one of
     * ["WORK", "WORKDD", "MOB", "FAX", "HOME", "OTHER"]
     *
     * @return Contact
     */
    public function addPhone(array $customFields, string $phone, string $enum = "WORK")
    {
        $id = $this->findPhoneId($customFields);
        $index = array_search($id, array_column($this->entity["custom_fields"], "id"));
        
        if ($index !== false) {
            $this->entity["custom_fields"][$index]["values"][] = [
                "value" => $phone,
                "enum" => $enum
            ];

            return $this;
        }

        $this->setCustomField($id, $phone, $enum);

        return $this;
    }

    /**
     * Get all contact phones
     * 
     * @param array $customFields - all custom fields of contacts
     * of contact (AccountApi::getAccountInfo()["custom_fields"]["contacts"])
     * 
     * @return array $contactPhones
     */
    public function getPhones(array $customFields) : array
    {
        $id = $this->findPhoneId($customFields);
        $index = array_search($id, array_column($this->entity["custom_fields"], "id"));

        if ($index !== false) {
            return array_reverse($this->entity["custom_fields"][$index]["values"]);
        }

        return [];
    }

    /**
     * Set first email address
     *
     * @param array $customFields - all custom fields of contacts
     * of contact (AccountApi::getAccountInfo()["custom_fields"]["contacts"])
     * @param string $email
     * @param string $enum - id of enum or one of
     * ["WORK", "PRIV", "OTHER"]
     *
     * @return Contact
     */
    public function setEmail(array $customFields, string $email, string $enum = "WORK")
    {
        $id = $this->findEmailId($customFields);
        $this->setCustomField($id, $email, $enum);

        return $this;
    }

    /**
     * Add email to exist email addresses
     *
     * @param array $customFields - all custom fields of contacts
     * of contact (AccountApi::getAccountInfo()["custom_fields"]["contacts"])
     * @param string $email
     * @param string $enum
     *
     * @return Contact
     */
    public function addEmail(array $customFields, string $email, string $enum = "WORK")
    {
        $id = $this->findEmailId($customFields);
        $index = array_search($id, array_column($this->entity["custom_fields"], "id"));
        
        if ($index !== false) {
            $this->entity["custom_fields"][$index]["values"][] = [
                "value" => $email,
                "enum" => $enum
            ];

            return $this;
        }

        $this->setCustomField($id, $email, $enum);

        return $this;
    }

    /**
     * Get all contact emails
     * 
     * @param array $customFields - all custom fields of contacts
     * of contact (AccountApi::getAccountInfo()["custom_fields"]["contacts"])
     * 
     * @return array $contactEmails
     */
    public function getEmails(array $customFields) : array
    {
        $id = $this->findEmailId($customFields);
        $index = array_search($id, array_column($this->entity["custom_fields"], "id"));

        if ($index !== false) {
            return $this->entity["custom_fields"][$index]["values"];
        }

        return [];
    }

    /**
     * Bind lead to contact
     *
     * @param int $id
     *
     * @return Contact
     */
    public function addLead(int $id)
    {
        $this->entity["leads"]["id"][] = $id;
        
        return $this;   
    }

    /**
     * Bind company to contact
     *
     * @param int $id
     *
     * @return Contact
     */
    public function setCompany(int $id)
    {
        $this->entity["company"]["id"] = $id;

        return $this;
    }

    /**
     * Set entity updated_by
     *
     * @param int $updatedBy
     *
     * @return Contact
     */
    public function setUpdatedBy(int $updatedBy)
    {
        $this->entity["updated_by"] = $updatedBy;

        return $this;
    }

    /**
     * Return entity updated_by
     * 
     * @return int
     */
    public function getUpdatedBy()
    {
        return $this->entity["updated_by"];
    }

    /**
     * Return entity company
     * 
     * ["id" => int, "name" => string]
     * 
     * @return array
     */
    public function getCompany()
    {
        return $this->entity["company"];
    }

    /**
     * Return entity leads
     * 
     * ["id" => int]
     * 
     * @return array
     */
    public function getLeads()
    {
        return $this->entity["leads"];
    }

    /**
     * Return entity customers
     * 
     * ["id" => int]
     * 
     * @return array
     */
    public function getCustomers()
    {
        return $this->entity["customers"];
    }

    /**
     * Get id of system phone custom field
     *
     * @param array $contactCustomFields
     *
     * @return mixed
     */
    private function findPhoneId($contactCustomFields)
    {
        $phoneId = null;
        foreach ($contactCustomFields as $field) {
            if (
                $field["is_system"]
                && ($field["name"] == "Телефон" || $field["name"] == "Phone")
            ) {
                $phoneId = $field["id"];
            }
        }

        return $phoneId;
    }

    /**
     * Get id of system email custom field
     *
     * @param array $contactCustomFields
     *
     * @return mixed
     */
    private function findEmailId($contactCustomFields)
    {
        $emailId = null;
        foreach ($contactCustomFields as $field) {
            if ($field["is_system"] && $field["name"] == "Email") {
                $emailId = $field["id"];
            }
        }

        return $emailId;
    }
}