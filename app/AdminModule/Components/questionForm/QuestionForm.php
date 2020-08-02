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
		$form->addSubmit('submit', $this->question ? 'Upravit' : 'Vytvořit');
		$form->onSuccess[] = [$this, 'processQuestionForm'];
		return $form;
	}

	public function processQuestionForm(Form $form, ArrayHash $values)
	{
		$this->testFacade->recordQuestion($values->text, $this->question);
		$stateText = $this->question ? 'upravena' : 'vytvořena';
		$this->flashMessage("Otázka byla úspěšně $stateText");
		$this->presenter->redirect('this', null);
	}

	public function render(): void
	{
		$this->template->question = $this->question;
		$this->template->render(__DIR__ . '/questionForm.latte');
	}
}

interface IQuestionFormFactory
{
	function create(?Question $question): QuestionForm;
}