<?php

namespace App\Presenters;

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
        $employee_name = $this->employeeModel->getEmployeeName($order_id->employee_id);
        $employee_id = $order_id->toArray()["employee_id"];

        $this->template->employee_id = $employee_id;
        $this->template->tube_production = $tube_production;
        $this->template->order_id = $order_id;
        $this->template->employee_name = $employee_name;
    }
    public function actionEdit(int $id): void
    {
        $order_value = $this->tubeProductionModel->getOrderById()->get($id);
        if (!$order_value) {
            $this->error('Uživatel nebyl nalezen');
        }
        if ($this->user->id == $order_value->toArray()["employee_id"]) {
            $this['editForm']->setDefaults($order_value->toArray());
            }
        else{
            $this->flashMessage("Nemáte oprávění upravit tuto zakázku","error");
        }
    }
    public function handleDelete(int $id)
    {
        $this->tubeProductionModel->deleteRecord($id);
        $this->flashMessage('Zakázka byla odstraněna.', 'success');
        $this->redirect('TubeProduction:production');
    }

    protected function createComponentEditForm(): Form
    {
        $form = new Form;
        $form->addText('order_id', 'číslo zakázky')
            ->addRule($form::LENGTH, 'Číslo zakázky může být krátné minimálně 6 číslic',[6,7])
            ->addRule($form::NUMERIC, 'Číslo zakázky se musí skládat pouze z číslic')
            ->setRequired('Vyplňte prosím %label');
        $form->addHidden('id');
        $diameter = $this->tubeDiameterModel->getDiameters();
        $form->addSelect('tube_diameter', 'Průměr: ', $diameter);

        $form->addText('made_quantity', 'počet kusů')
            ->addRule($form::NUMERIC, 'Počet kusů se musí skládat pouze z číslic')
            ->addRule($form::MAX_LENGTH, 'Počet kusů může mít maximálně %d znaků', 4)
            ->setRequired('Vyplňte prosím %label');

        $shifts = [
            '1' => 'Ranní',
            '2' => 'Odpolední',
            '3' => 'Noční',
        ];

        $form->addSelect('shift_id', 'Směna: ', $shifts);
        $form->setDefaults(["shift" => 3, "diameter" => 2]);
        $form->addSubmit('save', 'Uložit');

        $form->onSuccess[] = [$this, 'editFormSucceeded'];
        return $form;
    }
    public function editFormSucceeded(Form $form, array $values): void
    {
       $this->tubeProductionModel->updateNewData(
           $values['id'],
           $values['order_id'],
           $values['tube_diameter'],
           $values['made_quantity'],
           $values['shift_id']);

        $this->flashMessage('Zakázka byla upravena.', 'success');
        $this->redirect('TubeProduction:production');
    }
}