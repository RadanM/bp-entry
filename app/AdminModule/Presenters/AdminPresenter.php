<?php
declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\Model\Facades\TestFacade;
use App\AdminModule\Components\{IQuestionsGridFactory, QuestionsGrid};
use Nette\Application\UI\Presenter;

class AdminPresenter extends Presenter
{
	/** @inject */
	public IQuestionsGridFactory $questionsGridFactory;

	/** @inject */
	public TestFacade $testFacade;

	private array $questions;

	protected function createComponentQuestionsGrid(): QuestionsGrid
	{
		return $this->questionsGridFactory->create($this->getQuestions());
	}

	private function getQuestions(): array
	{
		return $this->questions ??= $this->testFacade->getQuestions();
	}
}