<?php
declare(strict_types=1);

namespace App\WebModule\Presenters;

use App\WebModule\Components\{ITestEntryFormFactory, TestEntryForm};
use Nette\Application\UI\Presenter;

final class HomepagePresenter extends Presenter
{
    /** @inject */
    public ITestEntryFormFactory $testEntryFormFactory;

    protected function createComponentTestEntryForm(): TestEntryForm
    {
        return $this->testEntryFormFactory->create();
    }
}
