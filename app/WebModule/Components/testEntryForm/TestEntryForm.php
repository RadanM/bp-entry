<?php
declare(strict_types=1);

namespace App\WebModule\Components;

use App\ApiModule\ApiHelper;
use Nette\Application\UI\{Control, Form};
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Nette\Utils\ArrayHash;

class TestEntryForm extends Control
{
	/** @var callable */
	public $onSuccess;

	protected function createComponentTestEntryForm(): Form
    {
        $form = new Form();
        $form->addEmail('email', 'Email')
            ->setRequired('Bez zadaného emailu nelze na test pokračovat');
        $form->addSubmit('send', 'Pokračovat');
        $form->onSuccess[] = [$this, 'processTestEntryForm'];
        return $form;
    }

    public function processTestEntryForm(Form $form, ArrayHash $values): void
    {
		$response = (new Client())->request('GET', ApiHelper::getUri('test/check'), [
			RequestOptions::QUERY => ["email" => $values->email]
		]);
		$this->onSuccess($response->getBody()->getContents());
    }

    public function render(): void
    {
        $this->template->render(__DIR__ . '/testEntryForm.latte');
    }
}

interface ITestEntryFormFactory
{
    function create(): TestEntryForm;
}