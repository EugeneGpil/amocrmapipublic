<?php
declare(strict_types=1);

namespace Amocrmapi\Entity;

use Amocrmapi\Traits\DefaultEntityTrait;
use Amocrmapi\Dependencies\EntityInterface;

/**
 * Class Lead
 *
 * @package Amocrmapi\Entity
 */
class Lead implements EntityInterface
{
	use DefaultEntityTrait;

    /**
     * Default name for creating leads
     */
    const LEAD_DEFAULT_NAME = "api lead";
    /**
     * Element type for note and task amoapi
     */
    const ELEMENT_TYPE = 2;

    /**
     * Lead constructor.
     */
    public function __construct()
	{
		$this->entity = [
			"id" => null,
            "sale" => null,
            "group_id" => null,
            "status_id" => null,
            "closed_at" => null,
            "created_at" => null,
            "created_by" => null,
            "updated_at" => null,
            "account_id" => null,
            "is_deleted" => null,
            "pipeline_id" => null,
            "main_contact" => null,
            "loss_reason_id" => null,
            "closest_task_at" => null,
            "responsible_user_id" => null,
            "name" => self::LEAD_DEFAULT_NAME,

            "tags" => "",
            "notes" => [],
            "tasks" => [],
            "custom_fields" => [],
            "company" => ["id" => ""],
			"contacts" => ["id" => []],

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
          if (isset($this->entity["contacts"]["id"])) {
               $this->entity["contacts_id"] = $this->entity["contacts"]["id"];
          }

          if (isset($this->entity["company"]["id"])) {
               $this->entity["company_id"] = $this->entity["company"]["id"];
          }

          if (!isset($this->entity["custom_fields"])) {
               return $this->entity;
          }

          foreach ($this->entity["custom_fields"] as &$customField) {

               if (
                    !isset($customField["values"][0]["enum"])
                    || !isset($customField["values"][0]["value"])
               ) {
                    continue;
               }

               $customField["values"][0]["value"] = 
                    $customField["values"][0]["enum"];
          }

          return $this->entity;
     }

    /**
     * Parse lead entity from amocrm response
     * 
     * @param array @data
     * 
     * @return Lead
     */
    public function parse(array $data)
    {
    	$data["tags"] = join(',', array_reverse(array_column($data["tags"], "name")));
    	$this->entity = $data;
    	
    	return $this;
    }

    /**
     * Bind contact to lead
     * 
     * @param int $id
     * 
     * @return Lead
     */
    public function addContact(int $id)
    {
    	$this->entity["contacts"]["id"][] = $id;

        return $this;	
    }

	/**
     * Return entity is_deleted
     * 
     * @return bool
     */
	public function getIsDeleted()
	{
		return $this->entity["is_deleted"];
	}

	/**
     * Return entity main_contact
     * 
     * ["id" => int]
     * 
     * @return array
     */
	public function getMainContact()
	{
		return $this->entity["main_contact"];
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
     * Set lead company
     *
     * @param int $id
     * 
     * @return Lead
     */
	public function setCompany(int $id)
	{
		$this->entity["company"]["id"] = $id;

		return $this;
	}

	/**
     * Return entity closed_at
     * 
     * @return int
     */
	public function getClosedAt()
	{
		return $this->entity["closed_at"];
	}

	/**
     * Return entity contacts
     * 
     * ["id" => int]
     * 
     * @return array
     */
	public function getContacts()
	{
		return $this->entity["contacts"];
	}

	/**
     * Return entity status_id
     * 
     * @return array
     */
	public function getStatusId()
	{
		return $this->entity["status_id"];
	}

	/**
     * Set entity status_id
     * 
     * @param int
     * 
     * @return Lead
     */
	public function setStatusId(int $statusId)
	{
		$this->entity["status_id"] = $statusId;

		return $this;
	}

	/**
     * Return entity sale
     * 
     * @return int
     */
	public function getSale()
	{
		return $this->entity["sale"];
	}

	/**
     * Set entity sale
     * 
     * @param int
     * 
     * @return Lead
     */
	public function setSale(int $sale)
	{
		$this->entity["sale"] = $sale;

		return $this;
	}

    /**
     * Return entity pipeline
     * 
     * ["id" => int]
     * 
     * @return array
     */
    public function getPipeline()
    {
        return $this->entity["pipeline"]["id"];
    }

	/**
     * Set entity pipeline
     * 
     * @param $id
     * 
     * @return Lead
     */
	public function setPipeline($id)
	{
		$this->entity["pipeline"] = $id;

        return $this;
     }
     
     /**
     * Return entity loss_reason_id
     * 
     * ["id" => int]
     * 
     * @return array
     */
     public function getLossReasonId()
     {
          return $this->entity["loss_reason_id"];
     }

     /**
      * Set entity loss_reason_id
      * 
      * @param $id
      * 
      * @return Lead
      */
     public function setLossReasonId($id)
     {
          $this->entity["loss_reason_id"] = $id;

          return $this;
     }
}