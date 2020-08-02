<?php
declare(strict_types=1);

namespace App\AdminModule\Components;

use Nette\Application\UI\Control;

class QuestionsGrid extends Control
{
	private array $questions;

	public function __construct(array $questions)
	{
		$this->questions = $questions;
	}

	public function render(): void
	{
		$this->template->questions = $this->questions;
		$this->template->render(__DIR__ . '/questionsGrid.latte');
	}
}

interface IQuestionsGridFactory
{
	function create(array $questions): QuestionsGrid;
}