<?php
declare(strict_types=1);

namespace App\WebModule\Components;

use Nette\Application\UI\{Control, Form};
use Nette\Utils\ArrayHash;

class ResultForm extends Control
{
	protected function createComponentResultForm(): Form
	{
		$form = new Form();
		$form->addSubmit('submit', 'Odpovědět');
		$form->onSuccess[] = [$this, 'processResultForm'];
		return $form;
	}

	public function processResultForm(Form $form, ArrayHash $values): void
	{

	}

	public function render(): void
	{
		$this->template->render(__DIR__ . '/resultForm.latte');
	}
}

interface IResultFormFactory
{
	function create(): ResultForm;
}