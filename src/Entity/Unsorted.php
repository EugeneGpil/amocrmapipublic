<?php
declare(strict_types=1);

namespace Amocrmapi\Entity;

use Amocrmapi\Dependencies\EntityInterface;

/**
 * Class Unsorted
 *
 * @package Amocrmapi\Entity
 */
class Unsorted implements EntityInterface
{
    /**
     * Default source name attribute for unsorted api
     */
    const DEFAULT_SOURCE_NAME = "amoapi source name";
    /**
     * Default from name attribute for unsorted api
     */
    const DEFAULT_FORM_NAME = "amoapi form name";
    /**
     * Default from page attribute for unsorted api
     */
    const DEFAULT_FORM_PAGE = "amoapi form page";
    /**
     * Default ip attribute for unsorted api
     */
    const DEFAULT_IP = "0.0.0.0";
    /**
     * Default referer attribute for unsorted api
     */
    const DEFAULT_REFERER = "amoapi referer";
    /**
     * Default from attribute for unsorted api
     */
    const DEFAULT_FROM = "amoapi from";
    /**
     * Default duration attribute for unsorted api
     */
    const DEFAULT_DURATION = '0';
    /**
     * Default link attribute for unsorted api
     */
    const DEFAULT_LINK = "amoapi link";

    /**
     * @var array $entity
     */
    private $entity;

    /**
     * Unsorted constructor.
     */
    public function __construct()
	{
		$this->entity = [
            'source_name' => self::DEFAULT_SOURCE_NAME,
            'source_uid' => sha1("amoapi_source_uid" . time()),
            'pipeline_id' => "0",
            'created_at' => time(),
            'incoming_entities' => [
                "leads" => [["custom_fields" => []]],
                "contacts" => [["custom_fields" => []]],
                "companies" => [["custom_fields" => []]]
            ],
            "incoming_lead_info" => [
                // form
                "form_id" => time(),
                "form_page" => self::DEFAULT_FORM_PAGE,
                "ip" => self::DEFAULT_IP,
                "service_code" => sha1("amoapi_service_code" . time()),
                "form_name" => self::DEFAULT_FORM_NAME,
                "form_send_at" => time(),
                "referer" => self::DEFAULT_REFERER,

                //sip
                'to' => time(),
                'from' => self::DEFAULT_FROM,
                'date_call' => time(),
                'duration' => self::DEFAULT_DURATION,
                'link' => self::DEFAULT_LINK,
                'uniq' => sha1("amoapi_uniq" . time()),
                'addNote' => false,
            ]
        ];
    }

	/**
     * Parse lead entity from amocrm response
     * 
     * @param array @data
     * 
     * @return Unsorted
     */
    public function parse(array $data)
    {
        foreach ($data as $ind => $val) {
            $this->entity[$ind] = $val;
        }

    	return $this;
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
     * Add unsorted note
     * 
     * @param string $text
     * @param string $entityType
     * @param int $noteType = 4 - common note
     *
     * @return Unsorted
     */
    public function addNote(string $text, string $entityType = "lead", int $noteType = 4)
    {
        $this->entity["incoming_entities"]["leads"][0]["notes"][] = [
            "note_type" => $noteType,
            "element_type" => $entityType,
            "text" => $text
        ];

        return $this;
    }
   
   /**
    * Set unsorted lead
    * 
    * @param Lead $lead
    *
    * @return Unsorted
    */
    public function setLead(Lead $lead)
    {
        $this->entity["incoming_entities"]["leads"][0] = $lead->prepare();

        return $this;
    }

    /**
     * Set unsorted 
     * 
     * @param Contact $contact
     *
     * @return Unsorted
     */
    public function setContact(Contact $contact)
    {
        $this->entity["incoming_entities"]["contacts"][0] = $contact->prepare();

        return $this;
    }

    /**
     * Set unsorted 
     * 
     * @param Company $company
     *
     * @return Unsorted
     */
    public function setCompany(Company $company)
    {
        $this->entity["incoming_entities"]["companies"][0] = $company->prepare();

        return $this;
    }

    /**
     * Set unsorted sourceName
     * 
     * @param string $sourceName
     *
     * @return Unsorted
     */
    public function setSourceName(string $sourceName)
    {
        $this->entity["source_name"] = $sourceName;
    
        return $this;
    }

    /**
     * Set unsorted sourceUid
     * 
     * @param string $sourceUid
     *
     * @return Unsorted
     */
    public function setSourceUid(string $sourceUid)
    {
        $this->entity["source_uid"] = $sourceUid;
    
        return $this;
    }

    /**
     * Set unsorted pipelineId
     * 
     * @param string $pipelineId
     *
     * @return Unsorted
     */
    public function setPipelineId(string $pipelineId)
    {
        $this->entity["pipeline_id"] = $pipelineId;
    
        return $this;
    }

    /**
     * Set unsorted 
     * 
     * @param int $formId
     *
     * @return Unsorted
     */
    public function setFormId(int $formId)
    {
        $this->entity["incoming_lead_info"]["form_id"] = $formId;
    
        return $this;
    }

    /**
     * Set unsorted formPage
     * 
     * @param string $formPage
     *
     * @return Unsorted
     */
    public function setFormPage(string $formPage)
    {
        $this->entity["incoming_lead_info"]["form_page"] = $formPage;
    
        return $this;
    }

    /**
     * Set unsorted ip
     * 
     * @param string $ip
     *
     * @return Unsorted
     */
    public function setIp(string $ip)
    {
        $this->entity["incoming_lead_info"]["ip"] = $ip;
    
        return $this;
    }

    /**
     * Set unsorted serviceCode
     * 
     * @param string $serviceCode
     *
     * @return Unsorted
     */
    public function setServiceCode(string $serviceCode)
    {
        $this->entity["incoming_lead_info"]["service_code"] = $serviceCode;
    
        return $this;
    }

    /**
     * Set unsorted formName
     * 
     * @param string $formName
     *
     * @return Unsorted
     */
    public function setFormName(string $formName)
    {
        $this->entity["incoming_lead_info"]["form_name"] = $formName;
    
        return $this;
    }

    /**
     * Set unsorted 
     * 
     * @param int $formSendAt
     *
     * @return Unsorted
     */
    public function setFormSendAt(int $formSendAt)
    {
        $this->entity["incoming_lead_info"]["form_send_at"] = $formSendAt;
    
        return $this;
    }

    /**
     * Set unsorted referer
     * 
     * @param string $referer
     *
     * @return Unsorted
     */
    public function setReferer(string $referer)
    {
        $this->entity["incoming_lead_info"]["referer"] = $referer;
    
        return $this;
    }

    /**
     * Set unsorted to
     * 
     * @param int $to
     *
     * @return Unsorted
     */
    public function setTo(int $to)
    {
        $this->entity["incoming_lead_info"]["to"] = $to;

        return $this;
    }

    /**
     * Set unsorted from
     * 
     * @param string $from
     *
     * @return Unsorted
     */
    public function setFrom(string $from)
    {
        $this->entity["incoming_lead_info"]["from"] = $from;

        return $this;
    }

    /**
     * Set unsorted dateCall
     * 
     * @param int $dateCall
     *
     * @return Unsorted
     */
    public function setDateCall(int $dateCall)
    {
        $this->entity["incoming_lead_info"]["date_call"] = $dateCall;

        return $this;
    }

    /**
     * Set unsorted duration
     * 
     * @param string $duration
     *
     * @return Unsorted
     */
    public function setDuration(string $duration)
    {
        $this->entity["incoming_lead_info"]["duration"] = $duration;

        return $this;
    }

    /**
     * Set unsorted link
     * 
     * @param string $link
     *
     * @return Unsorted
     */
    public function setLink(string $link)
    {
        $this->entity["incoming_lead_info"]["link"] = $link;

        return $this;
    }

    /**
     * Set unsorted uniq
     * 
     * @param string $uniq
     *
     * @return Unsorted
     */
    public function setUniq(string $uniq)
    {
        $this->entity["incoming_lead_info"]["uniq"] = $uniq;

        return $this;
    }

    /**
     * Set unsorted addNote
     * 
     * @param bool $addNote
     *
     * @return Unsorted
     */
    public function setAddNote(bool $addNote)
    {
        $this->entity["incoming_lead_info"]["add_note"] = $addNote;

        return $this;
    }
}