<?php
declare(strict_types=1);

namespace App\WebModule\Components;

use App\Model\Question;
use Nette\Application\UI\{Control, Form};
use Nette\Utils\ArrayHash;

class ResultForm extends Control
{
	/** @var callable */
	public $onSuccess;
	private Question $question;
	private array $answers;

	public function __construct(Question $question)
	{
		$this->question = $question;
	}

	protected function createComponentResultForm(): Form
	{
		$form = new Form();
		$form->addHidden('questionId', $this->question->id);
		$form->addRadioList('answer', $this->question->text, $this->getAnswers())
			->setRequired('Musí být vyplněna alespoň jedna odpověď.');
		$form->addSubmit('submit', 'Odpovědět');
		$form->onSuccess[] = [$this, 'processResultForm'];
		return $form;
	}

	public function processResultForm(Form $form, ArrayHash $values): void
	{
		$this->onSuccess((int)$values->questionId, (int)$values->answer);
	}

	public function render(): void
	{
		$this->template->render(__DIR__ . '/resultForm.latte');
	}

	private function getAnswers(): array
	{
		if (!isset($this->answers)) {
			shuffle($this->question->answers);
			$this->answers = [];
			foreach ($this->question->answers as $answer) {
				$this->answers[$answer->id] = $answer->text;
			}
		}
		return $this->answers;
	}
}

interface IResultFormFactory
{
	function create(Question $question): ResultForm;
}