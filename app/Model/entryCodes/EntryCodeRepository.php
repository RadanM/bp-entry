<?php
declare(strict_types=1);

namespace App\Model;

use Nextras\Orm\Repository\Repository;

final class EntryCodeRepository extends Repository
{
    static function getEntityClassNames(): array
    {
        return [EntryCode::class]// TODO: Implement getEntityClassNames() method.;
    }
}