<?php
declare(strict_types=1);

namespace App\Model;

use Nextras\Orm\Entity\Entity;

/**
 * @property int $id {primary}
 * @property Question $question {m:1 Question, oneSided=true}
 * @property Answer $answer {m:1 Question, oneSided=true}
 * @property EntryCode $entryCode {m:1 EntryCode, oneSided=true}
 */
class Result extends Entity
{

}