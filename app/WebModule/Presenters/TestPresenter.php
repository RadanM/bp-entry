<?php
declare(strict_types=1);

namespace App\WebModule\Presenters;

use App\WebModule\Components\{ITestStartFormFactory, TestStartForm};
use Nette\Application\UI\Presenter;
use Nette\Utils\Strings;

class TestPresenter extends Presenter
{
	/** @inject */
	public ITestStartFormFactory $testStartFormFactory;

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

	}

	protected function createComponentTestStartForm(): TestStartForm
	{
		$form = $this->testStartFormFactory->create($this->email);
		$form->onSuccess[] = function (string $code, string $email) {
			$this->redirect("Test:question", [$code, $email]);
		};
		return $form;
	}
}