<?php
declare(strict_types=1);

namespace App\Model;

use Nextras\Orm\Repository\Repository;

final class ResultRepository extends Repository
{
    static function getEntityClassNames(): array
    {
        return [Result::class];
    }
}