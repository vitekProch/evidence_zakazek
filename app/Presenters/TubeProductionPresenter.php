<?php

namespace App\Presenters;

use Nette\Application\AbortException;

class TubeProductionPresenter extends BasePresenter
{
    public function renderProduction()
    {
        $tube_production = $this->tubeProductionModel->getTubeProduction();
        $this->template->tube_production = $tube_production;
    }
}