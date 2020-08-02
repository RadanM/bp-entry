<?php
declare(strict_types=1);

namespace App\WebModule\Presenters;

use App\ApiModule\ApiHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use App\WebModule\Components\{IResultFormFactory, ITestStartFormFactory, ResultForm, TestStartForm};
use Nette\Application\UI\Presenter;
use Nette\Utils\Strings;

class TestPresenter extends Presenter
{
	/** @inject */
	public ITestStartFormFactory $testStartFormFactory;

	/** @inject */
	public IResultFormFactory $resultFormFactory;

	private ?string $email;

	public function actionDefault(?string $email): void
	{
		if (!$email || !filter_var(Strings::trim($email, '"'), FILTER_VALIDATE_EMAIL)) {
			$this->redirect('Homepage:default');
		}
		$this->email = $email;
	}

	public function actionQuestion(string $code, string $email)
	{
		try {
			(new Client())->request('GET', ApiHelper::getUri('test/check-code'), [
				RequestOptions::QUERY => ["code" => $code, "email" => $email]
			]);
		} catch (GuzzleException $e) {
			$this->flashMessage('Kombinace vstupního kódu a hesla je špatná.');
			$this->presenter->redirect('Test:default', $email);
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
		return $this->resultFormFactory->create();
	}
}