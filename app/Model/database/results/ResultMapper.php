<?php
declare(strict_types=1);

namespace App\Model;

use Nextras\Orm\Mapper\Mapper;

class ResultMapper extends Mapper
{
	protected $tableName = 'results';

	public function getCorrectAnswersCount(string $entryCode): int
	{
		$builder = $this->builder()
			->leftJoin('results', '[entry_codes]', 'ec',
				'[results.entryCode] = [ec.id]')
			->leftJoin('results', '[answers]', 'a',
				'[results.answer] = [a.id]')
			->where('ec.code = %i', $entryCode)
			->andWhere('a.right = 1');
		return $this->connection->queryByQueryBuilder($builder)->count();
	}
}