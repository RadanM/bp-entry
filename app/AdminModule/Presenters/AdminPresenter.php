<?php
declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\Model\Facades\TestFacade;
use App\AdminModule\Components\{IQuestionFormFactory, IQuestionsGridFactory, QuestionForm, QuestionsGrid};
use Nette\Application\UI\Presenter;

class AdminPresenter extends Presenter
{
	/** @inject */
	public IQuestionsGridFactory $questionsGridFactory;

	/** @inject */
	public IQuestionFormFactory $questionFormFactory;

	/** @inject */
	public TestFacade $testFacade;

	private array $questions;
	private ?int $questionId;

	public function actionDefault(?int $questionId): void
	{
		$this->questionId = $questionId;
	}

	public function handleDeleteQuestion(int $questionId): void
	{
		if ($question = $this->getQuestions()[$questionId] ?? null) {
			$this->testFacade->deleteQuestion($question);
			unset($this->getQuestions()[$questionId]);
		}
		$this->redirect('this', null);
	}

	protected function createComponentQuestionsGrid(): QuestionsGrid
	{
		return $this->questionsGridFactory->create($this->getQuestions());
	}

	protected function createComponentQuestionForm(): QuestionForm
	{
		return $this->questionFormFactory->create($this->getQuestions()[$this->questionId] ?? null);
	}

	private function getQuestions(): array
	{
		return $this->questions ??= $this->testFacade->getQuestions();
	}
}