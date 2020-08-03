<?php
declare(strict_types=1);

namespace App\Model;

use Nextras\Orm\Mapper\Mapper;

class QuestionMapper extends Mapper
{
	protected $tableName = 'questions';

	public function getRandomQuestion(): array
	{
		$builder = $this->builder()
			->select('a.*, questions.text as question_text')
			->leftJoin('questions', '[answers]', 'a',
				'[questions.id] = [a.question_id]')
			->addOrderBy('RAND()')
			->limitBy(4);
		return $this->connection->queryByQueryBuilder($builder)->fetchAll();
	}
}