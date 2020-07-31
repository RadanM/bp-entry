<?php
declare(strict_types=1);

namespace App\ApiModule\V1;

use App\ApiModule\BaseController;
use Apitte\Core\Annotation\Controller\{ControllerPath};

/**
 * @ControllerPath("/v1")
 */
abstract class BaseV1Controller extends BaseController
{

}