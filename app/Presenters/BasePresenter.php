<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

use App\Model\EmployeeModel;
use App\Model\TubeDiameterModel;
use App\Model\TubeExcessModel;
use App\Model\TubeProductionModel;
use App\Model\SignModel;
use App\Model\ShiftModel;

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

    /**
     * @var SignModel
     * @inject
     */
    public SignModel $signModel;

    /**
     * @var ShiftModel
     * @inject
     */
    public ShiftModel $shiftModel;

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
        bdump('SOCKA');
        if ($this->user->isLoggedIn())
        {
            $activeShift = $this->shiftModel->getShiftById($this->user->getIdentity()->getData()['shift_id']);
            $this->template->layout = $activeShift;
        }

    }
}