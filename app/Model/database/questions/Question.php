<?php
declare(strict_types=1);

namespace App\Model;

use Nextras\Orm\Entity\Entity;

/**
 * @property int $id {primary}
 * @property string $text
 */
class Question extends Entity
{
	/** @var int */
	public const ANSWERS_COUNT = 4;
}