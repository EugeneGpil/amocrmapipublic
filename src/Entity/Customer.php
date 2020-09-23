<?php
declare(strict_types=1);

namespace Amocrmapi\Entity;

use Amocrmapi\Traits\DefaultEntityTrait;
use Amocrmapi\Dependencies\EntityInterface;

/**
 * ClassCustomert
 *
 * @package Amocrmapi\Entity
 */
class Customer implements EntityInterface
{
    use DefaultEntityTrait;

    /**
     * Default name for customer
     */
    const CUSTOMER_DEFAULT_NAME = "new api customer";
    /**
     * Element type for note and task amoapi
     */
    const ELEMENT_TYPE = 12;

    /**
     * Customer constructor.
     */
    public function __construct()
    {
        $this->entity = [
            "id" => null,
            "created_at" => null,
            "updated_at" => null,
            "created_by" => null,
            "responsible_user_id" => null,
            "name" => self::CUSTOMER_DEFAULT_NAME,
            
            "status_id" => null,
            "next_date" => 0,
            "next_price" => null,
            "periodicity" => null,
            "period_id" => null,

            "contacts_id" => null,
            "contacts" => [],
            "company_id" => null,

            "tags" => "",
            "custom_fields" => [],

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
        return $this->entity;
    }

    /**
     * Parse customer entity from amocrm response
     * 
     * @param array @data
     *
     * @return Customer
     */
    public function parse(array $data)
    {
        $this->entity = $data;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getNextDate() : int
    {
        return $this->entity["next_date"];
    }

    /**
     * @param int $nextDate
     * 
     * @return Customer
     */
    public function setNextDate(int $nextDate)
    {
        $this->entity["next_date"] = $nextDate;

        return $this;
    }

    /**
     * @return int
     */
    public function getNextPrice() : int
    {
        return $this->entity["next_price"];
    }

    /**
     * @param int $nextPrice
     * 
     * @return Customer
     */
    public function setNextPrice(int $nextPrice)
    {
        $this->entity["next_price"] = $nextPrice;

        return $this;
    }

    /**
     * @return int
     */
    public function getPeriodecity() : int
    {
        return $this->entity["periodicity"];
    }

    /**
     * @param int $periodicity
     * 
     * @return Customer
     */
    public function setPeriodecity(int $periodicity)
    {
        $this->entity["periodicity"] = $periodicity;

        return $this;
    }

    /**
     * @return int
     */
    public function getPeriodId() : int
    {
        return $this->entity["period_id"];
    }

    /**
     * @param int $periodId
     * 
     * @return Customer
     */
    public function setPeriodId(int $periodId)
    {
        $this->entity["period_id"] = $periodId;

        return $this;
    }

    /**
     * @return array (int)
     */
    public function getContactsId() : array
    {
        return $this->entity["contacts_id"];
    }

    /**
     * @param array (int) $contactsId
     * 
     * @return Customer
     */
    public function setContactsId(array $contactsId)
    {
        $this->entity["contacts_id"] = $contactsId;

        return $this;
    }

    /**
     * @return int
     */
    public function getCompanyId() : int
    {
        return $this->entity["company_id"];
    }

    /**
     * @param int $companyId
     * 
     * @return Customer
     */
    public function setCompanyId(int $companyId)
    {
        $this->entity["company_id"] = $companyId;

        return $this;
    }

    /**
     * Set items to unlink from customer
     * 
     * @param array $unlink ["contacts_id" => array (int), "company_id" => int]
     * 
     * @return Customer
     */
    public function setUnlink(array $unlink)
    {
        $this->entity["unlink"] = $unlink;

        return $this;
    }

    /**
     * @param array (int) $contacts
     * 
     * @return Customer
     */
    public function setContacts(array $contacts)
    {
        $this->entity["contacts"] = $contacts;

        return $this;
    }

    /**
     * @return array
     */
    public function getContacts() : array
    {
        return $this->entity["contacts"];
    }
    /**
     * @param int $statusId
     * 
     * @return Customer
     */
    public function setStatusId(int $statusId)
    {
        $this->entity["status_id"] = $statusId;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatusId() : int
    {
        return $this->entity["status_id"];
    }
}
