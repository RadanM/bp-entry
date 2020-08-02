<?php
declare(strict_types=1);

namespace App\WebModule\Presenters;

use Nette\Application\UI\Presenter;
use Nette\Utils\Strings;

class TestPresenter extends Presenter
{
	public function actionDefault(?string $email)
	{
		if (!$email || !filter_var(Strings::trim($email, '"'), FILTER_VALIDATE_EMAIL)) {
			$this->redirect('Homepage:default');
		}
	}
}