<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette\Application\UI\Form;



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

        $tube_production = $this->tubeProductionModel->getTubeProduction(7,0);
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
        $form->addText('order_id', 'číslo zakázky')
            ->addRule($form::LENGTH, 'Číslo zakázky může být krátné minimálně 6 číslic',[6,7])
            ->addRule($form::NUMERIC, 'Číslo zakázky se musí skládat pouze z číslic')
            ->setRequired('Vyplňte prosím %label');

        $diameters = $this->tubeDiameterModel->getDiameters();
        $form->addSelect('diameters', 'Průměr: ', $diameters);

        $form->addText('made_quantity', 'počet kusů')
            ->addRule($form::NUMERIC, 'Počet kusů se musí skládat pouze z číslic')
            ->addRule($form::MAX_LENGTH, 'Počet kusů může mít maximálně %d znaků', 4)
            ->setRequired('Vyplňte prosím %label');
        $form->addSubmit('send', 'Uložit');
        $form->onSuccess[] = [$this, 'orderTubeFormSucceeded'];
        return $form;
    }

    public function orderTubeFormSucceeded(\stdClass $data): void
    {
        $name = $this->user->getIdentity()->shift_id;
        $this->tubeProductionModel->insertNewData($data->order_id, $this->getUser()->id, $data->diameters, $data->made_quantity,$name);
        $this->flashMessage('Zakázka byla uložena', 'success');
        $this->redirect('this');
    }
}

