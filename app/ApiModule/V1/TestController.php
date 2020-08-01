<?php
declare(strict_types=1);

namespace App\ApiModule\V1;

use Apitte\Core\Http\{ApiRequest, ApiResponse};
use App\Model\facades\TestFacade;
use Apitte\Core\Annotation\Controller\{ControllerPath, ControllerId, Id, Path, Method, RequestParameters,
	RequestParameter};
use Nette\Http\IResponse;

/**
 * @ControllerPath("/test")
 * @ControllerId("test")
 */
class TestController extends BaseV1Controller
{
	private TestFacade $testFacade;

	public function __construct(TestFacade  $testFacade)
	{
		$this->testFacade = $testFacade;
	}

	/**
	 * @Path("/check")
	 * @Id("check")
	 * @Method("GET")
	 * @RequestParameters({
	 	@RequestParameter(name="email", in="query", type="string", description="User e-mail")
*	 })
	 */
	public function checkEmail(ApiRequest $request, ApiResponse $response)
	{
		return $request->getParameter('email');
	}
}