<?php
declare(strict_types=1);

namespace App\Model;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;

/**
 * @method ICollection|Answer[] getRandomQuestion()
 */
final class QuestionRepository extends Repository
{
    static function getEntityClassNames(): array
    {
        return [Question::class];
    }
}