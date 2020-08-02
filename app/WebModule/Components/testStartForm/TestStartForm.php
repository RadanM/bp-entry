<?php
declare(strict_types=1);

namespace App\WebModule\Components;

use App\ApiModule\ApiHelper;
use Nette\Application\UI\{Control, Form};
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Nette\Http\IResponse;
use Nette\Utils\ArrayHash;

class TestStartForm extends Control
{
	/** @var callable */
	public $onSuccess;
	private string $email;

	public function __construct(string $email)
	{
		$this->email = $email;
	}

	protected function createComponentTestStartForm(): Form
	{
		$form = new Form();
		$form->addText('code', 'Vstupní kód')
			->setRequired('Musíte nejprve zadat vstupní kód');
		$form->addSubmit('submit', 'Pokračovat na test');
		$form->onSuccess[] = [$this, 'processTestStartForm'];
		return $form;
	}

	public function processTestStartForm(Form $form, ArrayHash $values): void
	{
		$response = (new Client())->request('GET', ApiHelper::getUri('test/check-entry'), [
			RequestOptions::QUERY => ["code" => $values->code, "email" => $this->email]
		]);
		if ($response->getStatusCode() === IResponse::S401_UNAUTHORIZED) {
			$form->addError('Kombinace vstupního kódu a hesla je špatná.');
			$this->presenter->redirect('this');
		} else {
			$this->onSuccess($values->code, $this->email);
		}
	}

	public function render(): void
	{
		$this->template->render(__DIR__ . '/testStartForm.latte');
	}
}

interface ITestStartFormFactory
{
	function create(string $email): TestStartForm;
}