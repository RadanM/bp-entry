<?php
declare(strict_types=1);

namespace App\WebModule\Presenters;

use App\ApiModule\ApiHelper;
use App\Model\Facades\TestFacade;
use App\Model\Question;
use GuzzleHttp\{Client, RequestOptions};
use GuzzleHttp\Exception\GuzzleException;
use App\WebModule\Components\{IResultFormFactory, ITestStartFormFactory, ResultForm, TestStartForm};
use Nette\Application\UI\Presenter;
use Nette\Utils\{Json, Strings};

class TestPresenter extends Presenter
{
	/** @inject */
	public ITestStartFormFactory $testStartFormFactory;

	/** @inject */
	public IResultFormFactory $resultFormFactory;

	private ?string $email;
	private ?string $code;
	private ?Question $question;

	/** @inject */
	public TestFacade $testFacade;

	public function actionDefault(?string $email): void
	{
		if (!$email || !filter_var(Strings::trim($email, '"'), FILTER_VALIDATE_EMAIL)) {
			$this->redirect('Homepage:default');
		}
		$this->email = $email;
	}

	public function actionQuestion(string $code, string $email): void
	{
		try {
			(new Client())->request('GET', ApiHelper::getUri('test/check-code'), [
				RequestOptions::QUERY => ["code" => $code, "email" => $email]
			]);
			$response = (new Client())->request('GET', ApiHelper::getUri('test/question'), [
				RequestOptions::QUERY => ["code" => $code]
			]);
			$this->code = $code;
			$this->email = $email;
			$this->question = $this->testFacade->getQuestionEntity(Json::decode($response->getBody()->getContents()));
		} catch (GuzzleException $e) {
			$this->flashMessage('Kombinace vstupního kódu a hesla je špatná.');
			$this->redirect('Test:default', $email);
		}
	}

	protected function createComponentTestStartForm(): TestStartForm
	{
		$form = $this->testStartFormFactory->create($this->email);
		$form->onSuccess[] = function (string $code, string $email) {
			$this->redirect("Test:question", [$code, $email]);
		};
		return $form;
	}

	protected function createComponentResultForm(): ResultForm
	{
		$form = $this->resultFormFactory->create($this->question);
		$form->onSuccess[] = function (int $questionId, int $answerId) {
			$response = (new Client())->request('POST', ApiHelper::getUri('test/save-answer'), [
				RequestOptions::JSON => [
					"code" => $this->code,
					"answer_id" => $answerId,
					"question_id" => $questionId
				]
			]);
			if ($response->getBody()->getContents() === true) {
				$this->redirect("Test:question", [$this->code, $this->email]);
			} else {
				$this->redirect("Test:result", [$this->code, $this->email]);
			}
		};
		return $form;
	}

	public function renderQuestion(): void
	{
		$this->template->question = $this->question ?? null;
	}
}