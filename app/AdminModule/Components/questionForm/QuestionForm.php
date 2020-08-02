<?php
declare(strict_types=1);

namespace App\AdminModule\Components;

use App\Model\Facades\TestFacade;
use App\Model\Question;
use Nette\Application\UI\{Control, Form};
use Nette\Utils\ArrayHash;

class QuestionForm extends Control
{
	private ?Question $question;
	private TestFacade $testFacade;
	private array $answers;

	public function __construct(?Question $question, TestFacade $testFacade)
	{
		$this->question = $question;
		$this->testFacade = $testFacade;
	}

	protected function createComponentQuestionForm(): Form
	{
		$form = new Form();
		$form->addText('text', 'Text otázky')
			->setRequired('Otázka musí mít povinně vyplněný text')
			->addRule(Form::MAX_LENGTH, 'Otázka může mít maximálně %d znaků', 255)
			->setDefaultValue($this->question ? $this->question->text : null);
		$iterator = 1;
		foreach ($this->getAnswers() as $id => $answer) {
			$form->addText("answer$id", "Odpověď $iterator")
				->setRequired('Každá otázka musí mít vyplněné' . Question::ANSWERS_COUNT . ' odpovědi')
				->setDefaultValue($this->question ? $answer->text : null);
			$iterator++;
			if (is_object($answer) && $answer->right) {
				$rightAnswerId = $answer->id;
			}
		}
		$form->addRadioList('correct', 'Správná odpověď', $this->getRadioCorrect())
			->setRequired('Vyberte jednu správnou odpověď')
			->setDefaultValue($rightAnswerId ?? null);
		$form->addSubmit('submit', $this->question ? 'Upravit' : 'Vytvořit');
		$form->onSuccess[] = [$this, 'processQuestionForm'];
		return $form;
	}

	public function processQuestionForm(Form $form, ArrayHash $values)
	{
		$this->testFacade->recordQuestion($values, $this->getAnswers(), $this->question);
		$stateText = $this->question ? 'upravena' : 'vytvořena';
		$this->flashMessage("Otázka byla úspěšně $stateText");
		$this->presenter->redirect('this', null);
	}

	public function render(): void
	{
		$this->template->question = $this->question;
		$this->template->render(__DIR__ . '/questionForm.latte');
	}

	private function getAnswers(): array
	{
		return $this->answers ??= $this->testFacade->getAnswers($this->question);
	}

	private function getRadioCorrect(): array
	{
		$range = range(1, Question::ANSWERS_COUNT);
		return $this->question ? array_combine(array_keys($this->getAnswers()), $range) : $range;
	}
}

interface IQuestionFormFactory
{
	function create(?Question $question): QuestionForm;
}