<?php
declare(strict_types=1);

namespace App\Model\Facades;

use App\Model\EntryCode;
use App\Model\Model;
use Nette\Mail\Mailer;
use Nette\Mail\Message;
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
}