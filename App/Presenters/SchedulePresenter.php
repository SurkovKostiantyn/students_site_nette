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

    /**
     * @throws AbortException
     */
    #[NoReturn] public function renderMain(): void // this function is called when the page is loaded
    {
        $this->getTemplate()->title = 'Розклад';
        $this->setView('schedule');
    }


}
