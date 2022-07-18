<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Model\EmployeeModel;
use App\Model\TubeDiameterModel;
use App\Model\TubeExcessModel;
use App\Model\TubeProductionModel;

class BasePresenter extends Nette\Application\UI\Presenter
{
    /**
     * @var TubeProductionModel
     * @inject
     */
    public TubeProductionModel $tubeProductionModel;

    /**
     * @var EmployeeModel
     * @inject
     */
    public EmployeeModel $employeeModel;

    /**
     * @var TubeDiameterModel
     * @inject
     */
    public TubeDiameterModel $tubeDiameterModel;

    /**
     * @var TubeExcessModel
     * @inject
     */
    public TubeExcessModel $tubeExcessModel;



    protected function createComponentSearchForm()
    {
        $form = new Form;
        $form->addText('search_value', 'Hledat:')
            ->setRequired(TRUE);
        $form->addSubmit('send', 'Search');
        $form->onSuccess[] = [$this, 'searchFormSucceeded'];
        return $form;
    }
    public function searchFormSucceeded(Form $form, $values): void
    {
        $this->redirect("Search:search", [$values->search_value]);
    }
        protected function renderLayout()
    {
        $names = $this->user->getIdentity()->getData();
        $this->template->layout = $names;
    }
}