<?php
declare(strict_types=1);

namespace App\Model;

use Nextras\Orm\Entity\Entity;

/**
 * @property int $id {primary}
 * @property Question $question {m:1 Question, oneSided=true}
 * @property string $text
 * @property boolean $right
 */
class Answer extends Entity
{

}