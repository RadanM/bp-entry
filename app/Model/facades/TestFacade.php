<?php
declare(strict_types=1);

namespace App\Model\Facades;

use App\Model\Answer;
use App\Model\EntryCode;
use App\Model\Model;
use App\Model\Question;
use Nette\Mail\Mailer;
use Nette\Mail\Message;
use Nette\Utils\ArrayHash;
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
			'email' => $email,
		])->fetch();
	}

	public function getQuestions(): array
	{
		return $this->model->questions->findAll()->fetchPairs('id');
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
}