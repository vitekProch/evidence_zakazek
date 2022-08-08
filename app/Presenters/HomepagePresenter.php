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
        $tube_production = $this->tubeProductionModel->getTubeProduction();
        $this['orderTubeForm']->setValues(array(
            'diameter' => $this->tubeProductionModel->getLastDiameter()
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

        $diameter = [
            '1' => 'Ø 6x1',
            '2' => 'Ø 6x0.8',
            '3' => 'Ø 8',
            '4' => 'Ø 10',
            '5' => 'Ø 12',
            '6' => 'Ø 15',
            '7' => 'Ø 18',
            '8' => 'Ø 22',
        ];

        $form->addSelect('diameter', 'Průměr: ', $diameter)
            ->setDefaultValue('2');

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
        $this->tubeProductionModel->insertNewData($data->order_id, $this->getUser()->id, $data->diameter, $data->made_quantity,$name);
        $this->flashMessage('Zakázka byla uložena', 'success');
        $this->redirect('this');
    }
}

