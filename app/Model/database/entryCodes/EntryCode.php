<?php
declare(strict_types=1);

namespace App\Model;

use Nette\Utils\Random;
use Nextras\Orm\Entity\Entity;

/**
 * @property int $id {primary}
 * @property string $mail
 * @property string $code
 */
class EntryCode extends Entity
{
	/** @var int */
	private const CODE_LENGHT = 6;

	public function generateCode(): void
	{
		$this->code = Random::generate(self::CODE_LENGHT, '0-9');
	}
}