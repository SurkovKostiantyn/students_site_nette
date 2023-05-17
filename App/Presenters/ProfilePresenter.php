<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;

final class ProfilePresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private readonly Nette\Database\Explorer $database
    ) {
        parent::__construct();
    }

    public function renderShow(): void // this function is called when the page is loaded
    {
        $this->setView('index');

        $this->getTemplate()->title = 'Профіль';

        $this->getTemplate()->profile = 'Профіль';

        $user = $this->getUser();

        $this->getTemplate()->user = $user;
    }
}
