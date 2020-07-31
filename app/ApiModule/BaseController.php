<?php
declare(strict_types=1);

namespace App\ApiModule;

use Apitte\Core\UI\Controller\IController;
use Apitte\Core\Annotation\Controller\{ControllerId, ControllerPath};

/**
 * @ControllerPath("/api")
 * @ControllerId("api")
 */
abstract class BaseController implements IController
{

}