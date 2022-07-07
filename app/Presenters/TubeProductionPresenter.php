<?php

namespace App\Presenters;

use Nette\Application\AbortException;
use Nette\Application\UI\Form;

class TubeProductionPresenter extends BasePresenter
{
    public function renderProduction()
    {
        $tube_production = $this->tubeProductionModel->getTubeProduction();
        $this->template->tube_production = $tube_production;
    }
    public function renderEdit(int $id): void
    {
        $tube_production = $this->tubeProductionModel->getTubeProduction();
        $order_id = $this->tubeProductionModel->getOrderById()->get($id);

        $this->template->tube_production = $tube_production;
        $this->template->order_id = $order_id;
    }
    public function actionEdit(int $id): void
    {
        $users_value = $this->tubeProductionModel->getOrderById()->get($id);
        if (!$users_value) {
            $this->error('Uživatel nebyl nalezen');
        }
        $this['editForm']->setDefaults($users_value->toArray());
    }

    protected function createComponentEditForm(): Form
    {
        $form = new Form;
        $form->addText('order_id', 'číslo zakázky')
            ->addRule($form::LENGTH, 'Číslo zakázky může být krátné minimálně 6 číslic',[6,7])
            ->addRule($form::NUMERIC, 'Číslo zakázky se musí skládat pouze z číslic')
            ->setRequired('Vyplňte prosím %label');
        $form->addText('employee_id', 'Jméno pracovníka')
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

        $shifts = [
            '1' => 'Ranní',
            '2' => 'Odpolední',
            '3' => 'Noční',
        ];

        $form->addSelect('shift', 'Směna: ', $shifts);

        $form->addSubmit('send', 'Uložit');
        $form->onSuccess[] = [$this, 'editFormSucceeded'];

        return $form;
    }
    public function editFormSucceeded(Form $form, array $values): void
    {
       $this->tubeProductionModel->updateNewData(
           $values->order_id,
           $values->employee_id,
           $values->diameter,
           $values->made_quantity,
           $values->shift);

        $this->flashMessage('Zakázka byla upravena.', 'success');
        $this->redirect('AdminProfile:admin');
    }
}