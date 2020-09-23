<?php
declare(strict_types=1);

namespace Amocrmapi\V2\Api;

use Amocrmapi\Entity\Task;
use Amocrmapi\V2\Traits\InitApiTrait;
use Amocrmapi\V2\Traits\SingletonTrait;
use Amocrmapi\V2\Traits\DefaultApiMethodsTrait;
use Amocrmapi\Dependencies\DefaultEntityApiInterface;

/**
 * Class TaskApi
 *
 * @package Amocrmapi\V2\Api
 */
class TaskApi implements DefaultEntityApiInterface
{
	use SingletonTrait, InitApiTrait, DefaultApiMethodsTrait;

    /**
     * Api link adds to .amocrm.ru
     */
    const LINK = "/api/v2/tasks";
    /**
     * Class name to entities methods
     */
    const ENTITY_CLASS = Task::class;
}
