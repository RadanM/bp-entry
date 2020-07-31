<?php
declare(strict_types=1);

namespace App\ApiModule\V1;

use Apitte\Core\Annotation\Controller\{ControllerPath, ControllerId, Path, Method};

/**
 * @ControllerPath("/test")
 * @ControllerId("test")
 */
class TestController extends BaseV1Controller
{
    /**
     * @Path("/")
     * @Method("GET")
     */
    public function index(): string
    {
        return 'work';
    }
}