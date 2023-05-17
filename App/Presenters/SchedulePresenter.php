<?php

declare(strict_types=1);

namespace App\Presenters;

use JetBrains\PhpStorm\NoReturn;
use Nette;
use Nette\Application\AbortException;

final class SchedulePresenter extends Nette\Application\UI\Presenter
{
//    public function __construct(
//        private readonly Nette\Database\Explorer $database
//    ) {
//        parent::__construct();
//    }


    private function getTheWholeList(): array
    {
        return [
            'First',
            'Second',
            'Third',
        ];
    }

    /**
     * @throws AbortException
     */
    #[NoReturn] public function renderMain(): void // this function is called when the page is loaded
    {
        if (!isset($this->getTemplate()->list)) {
            $this->getTemplate()->list = $this->getTheWholeList();
        }

        $this->getTemplate()->title = 'Розклад';
        $this->setView('schedule');
    }


    public function handleUpdate(int $id): void
    {
        $this->getTemplate()->list = $this->isAjax()
            ? []
            : $this->getTheWholeList();
        $this->getTemplate()->list[$id] = 'Updated item';
        $this->redrawControl('itemsContainer');
    }
}
