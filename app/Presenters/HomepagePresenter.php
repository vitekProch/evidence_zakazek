<?php

declare(strict_types=1);

namespace App\Presenters;

use mysql_xdevapi\DatabaseObject;
use mysql_xdevapi\RowResult;
use Nette\Application\UI\Form;
use App\Exceptions;


class HomepagePresenter extends BasePresenter
{
    public function startup()
    {

        parent::startup();
        if (!$this->getUser()->isLoggedIn()) {
            $this->flashMessage('Pro vytvoření nové zakázky je nutné se přihlásit!', 'error');
            $this->redirect('Sign:in');
        }
    }
    public function renderDefault($data)
    {
        $tube_diameters = $this->tubeDiameterModel->getDiameters();

        $tube_production = $this->tubeProductionModel->getTubeProduction(5,0);
        $this['orderTubeForm']->setValues(array(
            'diameters' => $this->tubeProductionModel->getLastDiameter()
        ), true);

        $this->template->tube_production = $tube_production;
        $this->template->tube_diameters = $tube_diameters;
        if (!isset($this->template->data)) {
            $this->template->data = $data;
        }
    }

    protected function createComponentOrderTubeForm(): Form
    {
        $form = new Form;

        $form->addText('order_id', 'Číslo Zakázky: ')
            ->addRule($form::LENGTH, 'Číslo zakázky může být krátné minimálně 6 číslic',[6,7])
            ->addRule($form::NUMERIC, 'Číslo zakázky se musí skládat pouze z číslic')
            ->setRequired('Vyplňte prosím %label');

        $form->addText('material_id', 'Číslo materiálu: ')
            ->addRule($form::LENGTH, 'Číslo materiálu může být krátné minimálně 6 číslic',[6,7])
            ->addRule($form::NUMERIC, 'Číslo materiálu se musí skládat pouze z číslic')
            ->setRequired('Vyplňte prosím %label');

        $diameters = $this->tubeDiameterModel->getDiameters();
        $form->addSelect('diameters', 'Průměr: ', $diameters);

        $form->addText('made_quantity', 'Počet kusů: ')
            ->addRule($form::NUMERIC, 'Počet kusů se musí skládat pouze z číslic')
            ->addRule($form::MAX_LENGTH, 'Počet kusů může mít maximálně %d znaků', 4)
            ->setRequired('Vyplňte prosím %label');
        $form->addSubmit('send', 'Uložit');
        $form->onSuccess[] = [$this, 'orderTubeFormSucceeded'];
        return $form;
    }

    public function orderTubeFormSucceeded(\stdClass $data): void
    {
        try {
            $name = $this->user->getIdentity()->shift_id;
            $shift_id = $this->shiftModel->getShiftByName($name);
            $this->tubeProductionModel->insertNewData($data->order_id, $data->material_id, $this->getUser()->id, $data->diameters, $data->made_quantity,$shift_id->shift_id);
            $this->flashMessage('Zakázka byla uložena', 'success');
            $this->redirect('this');
        }

        catch (Exceptions\DuplicateNameException $e){
            $this->flashMessage("Číslo zakázky již existuje", 'error');
        }
    }
    public function handleShow(int $id, array $pproduct)
    {

        $this->template->id = $id;
        $this->template->productt = $pproduct;
        if ($this->isAjax()) {
            $this->payload->isModal = TRUE;
            $this->redrawControl("modal");

        }
    }
}
