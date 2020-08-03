<?php
declare(strict_types=1);

namespace App\Model\Facades;

use App\Model\{Answer, EntryCode, Model, Question, Result};
use Nette\Mail\{Mailer, Message};
use Nette\Utils\{ArrayHash, Strings};
use Nextras\Orm\Entity\IEntity;

class TestFacade
{
	private string $mailSender;
	private Mailer $mailer;
	private Model $model;

	public function __construct(string $mailSender, Model $model, Mailer $mailer)
	{
		$this->mailSender = $mailSender;
		$this->model = $model;
		$this->mailer = $mailer;
	}

	public function checkEmail(string $email): EntryCode
	{
		if (!$entryCode = $this->model->entryCodes->findBy(['mail' => $email])->fetch()) {
			$entryCode = new EntryCode();
			$entryCode->generateCode();
			$entryCode->mail = $email;
			$this->model->entryCodes->persistAndFlush($entryCode);
			$this->sendEmailWithEntryCode($entryCode);
		}
		return $entryCode;
	}

	private function sendEmailWithEntryCode(EntryCode $entryCode): void
	{
		$message = new Message();
		$message->addTo($entryCode->mail)
			->setFrom($this->mailSender)
			->setBody("Váš vygenerovaný kód je: $entryCode->code");
		$this->mailer->send($message);
	}

	public function checkEntryCode(string $code, string $email): ?IEntity
	{
		return $this->model->entryCodes->findBy([
			'code' => $code,
			'mail' => Strings::trim($email, '"'),
		])->fetch();
	}

	public function getQuestions(): array
	{
		return $this->model->questions->findAll()->fetchPairs('id');
	}

	public function getQuestionForTest(string $code): array
	{
		return $this->model->questions->getRandomQuestion($code);
	}

	public function getQuestionEntity(array $dbRowData): ?Question
	{
		$result = [];
		foreach ($dbRowData as $answer) {
			if (!isset($question)) {
				$question = new Question();
				$question->id = $answer->question_id;
				$question->text = $answer->question_text;
			}
			$entity = new Answer();
			$entity->id = $answer->id;
			$entity->right = $answer->right;
			$entity->text = $answer->text;
			$result[] = $entity;
		}
		if (isset($question)) {
			$question->answers = $result;
		}
		return $question ?? null;
	}

	public function recordQuestion(ArrayHash $values, array $answers, ?Question $question): void
	{
		$question = $question ?? new Question();
		$question->text = $values->text;
		$this->model->persist($question);
		foreach ($answers as $id => $answer) {
			$answer = $answer instanceof Answer ? $answer : new Answer();
			$answer->question = $question;
			$answer->text = $values->{"answer$id"};
			$answer->right = $values->correct === $id;
			$this->model->persist($answer);
		}
		$this->model->flush();
	}

	public function deleteQuestion(Question $question): void
	{
		$this->model->remove($question);
		$this->model->flush();
	}

	public function getAnswers(?Question $question): array
	{
		return $question ?
			$this->model->answers->findBy(['question' => $question->id])->fetchPairs('id') :
			range(1, Question::ANSWERS_COUNT);
	}

	public function saveAnswer(string $code, int $answerId, int $questionId): bool
	{
		$result = new Result();
		$result->entryCode = $entryCode = $this->model->entryCodes->findBy(['code' => $code])->fetch();
		$result->answer = $this->model->answers->findById($answerId)->fetch();
		$result->question = $this->model->questions->findById($questionId)->fetch();
		$this->model->persistAndFlush($result);
		return $this->getAnswersCount($entryCode->id);
	}

	public function getAnswersCount(int $entryCodeId)
	{
		return $this->model->results->findBy(['entry_code' => $entryCodeId])->count() < Question::QUESTIONS_COUNT;
	}
}