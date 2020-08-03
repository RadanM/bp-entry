<?php
declare(strict_types=1);

namespace App\Model;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\Entity;

/**
 * @property int $id {primary}
 * @property string $text
 * @property ICollection|Answer[] $answers {virtual}
 */
class Question extends Entity
{
	/** @var int */
	public const ANSWERS_COUNT = 4;

	/** @var int */
	public const QUESTIONS_COUNT = 10;
}