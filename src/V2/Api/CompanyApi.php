<?php
declare(strict_types=1);

namespace Amocrmapi\V2\Api;

use Amocrmapi\Entity\Company;
use Amocrmapi\V2\Traits\InitApiTrait;
use Amocrmapi\V2\Traits\SingletonTrait;
use Amocrmapi\Dependencies\DefaultEntityApiInterface;
use Amocrmapi\V2\Traits\DefaultApiMethodsTrait;

/**
 * Class CompanyApi
 *
 * @package Amocrmapi\V2\Api
 */
class CompanyApi implements DefaultEntityApiInterface
{
	use SingletonTrait, InitApiTrait, DefaultApiMethodsTrait;

    /**
     * Api link adds to .amocrm.ru
     */
    const LINK = "/api/v2/companies";
    /**
     * Class name to entities methods
     */
    const ENTITY_CLASS = Company::class;
}
