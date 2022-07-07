<?php

namespace App\Presenters;
use App\Model\EmployeeModel;
use App\Model\TubeDiameterModel;
use Nette;
use App\Model\TubeProductionModel;
use Nette\Application\UI\Form;

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

    protected function beforeRender(): void
    {
        parent::beforeRender();
        $this->redrawControl('title');
        $this->redrawControl('content');
    }
}