<?php
declare(strict_types=1);

namespace App\Model;

use Nextras\Orm\Mapper\Mapper;

class QuestionMapper extends Mapper
{
	protected $tableName = 'questions';

	public function getRandomQuestion(string $code): array
	{
		$builder = $this->builder()
			->select('a.*, questions.text as question_text')
			->leftJoin('questions', '[answers]', 'a',
				'[questions.id] = [a.question_id]')
			->where('question_id NOT IN (
				SELECT r.question_id FROM results r
				LEFT JOIN entry_codes ec ON ec.id = r.entry_code_id
				WHERE ec.code = %i
			)', $code)
			->addOrderBy('RAND()')
			->limitBy(4);
		return $this->connection->queryByQueryBuilder($builder)->fetchAll();
	}
}