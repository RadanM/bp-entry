<?php
declare(strict_types=1);

namespace App\ApiModule\V1;

use Apitte\Core\Http\{ApiRequest, ApiResponse};
use App\Model\Facades\TestFacade;
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
	 *	})
	 */
	public function checkEmail(ApiRequest $request): string
	{
		return $this->testFacade->checkEmail($request->getParameter('email'))->mail;
	}

	/**
	 * @Path("/check-code")
	 * @Id("checkCode")
	 * @Method("GET")
	 * @RequestParameters({
	 *   	@RequestParameter(name="code", in="query", type="string", description="User entry code"),
	 *		@RequestParameter(name="email", in="query", type="string", description="User e-mail")
	 *	})
	 */
	public function checkCode(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		$entryCode = $this->testFacade->checkEntryCode(
			$request->getParameter('code'),
			$request->getParameter('email')
		);
		return $response->withStatus($entryCode ? IResponse::S200_OK : IResponse::S401_UNAUTHORIZED);
	}

	/**
	 * @Path("/question")
	 * @Id("getQuestion")
	 * @Method("GET")
	 * @RequestParameters({
	 *   	@RequestParameter(name="code", in="query", type="string", description="User entry code"),
	 *	})
	 */
	public function getQuestion(ApiRequest $request): array
	{
		return $this->testFacade->getQuestionForTest($request->getParameter('code'));
	}

	/**
	 * @Path("/save-answer")
	 * @Id("saveAnswer")
	 * @Method("POST")
	 */
	public function saveAnswer(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		$parameters = $request->getJsonBody();
		$next = $this->testFacade->saveAnswer($parameters['code'], $parameters['answer_id'], $parameters['question_id']);
		return $response->withStatus(IResponse::S200_OK)
			->writeBody($next)
			->withHeader('Content-Type', 'application/json');
	}

	/**
	 * @Path("/result")
	 * @Id("result")
	 * @Method("GET")
	 * @RequestParameters({
	 *   	@RequestParameter(name="code", in="query", type="string", description="User entry code"),
	 *		@RequestParameter(name="email", in="query", type="string", description="User e-mail")
	 *	})
	 */
	public function getResult(ApiRequest $request, ApiResponse $response)
	{
		$code = $request->getParameter('code');
		$mail = $request->getParameter('mail');
		if (!$this->testFacade->checkEntryCode($code, $mail)) {
			return $response->withStatus(IResponse::S401_UNAUTHORIZED);
		}
		return $this->testFacade->getCorrectAnswersCount($code);
	}
}