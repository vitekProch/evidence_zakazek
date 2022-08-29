<?php

namespace App\Presenters;

use Nette\Application\UI\Form;
use Nette\Forms\Controls\SubmitButton;

class CreateExcessPresenter extends BasePresenter
{
    public function renderCreateExcess()
    {
        $getExcess = $this->tubeExcessModel->getExcess();
        $this->template->getExcess = $getExcess;
    }

    public function renderEdit($id)
    {
        $this->setView('excess-update-modal');
        $excess_value = $this->tubeExcessModel->getExcessByOrderId()->get($id);
        if (!$excess_value) {
            $this->error('Zakázka nebyla nalezena');
        }
        $this['edittForm']->setDefaults($excess_value->toArray());
        bdump('neco');
    }

    protected function createComponentExcessForm(): Form
    {
        $form = new Form();
        $form->addText('order_id', 'Číslo zakázky:')
            ->addRule($form::LENGTH, 'Číslo zakázky může být krátné minimálně 6 číslic',[6,7])
            ->addRule($form::NUMERIC, 'Číslo zakázky se musí skládat pouze z číslic')
            ->setRequired('Vyplňte prosím %label');

        $form->addText('quantity', 'Počet navíc:')
            ->setRequired('Vyplňte prosím %label')
            ->addRule($form::NUMERIC, 'Počet kusů se musí skládat pouze z číslic')
            ->addRule($form::MAX_LENGTH, 'Počet kusů může mít maximálně %d znaků', 4);
        $diameters = $this->tubeDiameterModel->getDiameters();
        $form->addSelect('diameters', 'Průměr: ', $diameters);

        $form->addSubmit('save', 'Uložit')
            ->onClick[] = [$this, 'send'];
        return $form;
    }

    public function send(SubmitButton $button) {
        $values = $button->getForm()->getValues();
            $excess = $this->tubeExcessModel->checkExcess($values['order_id']);
            if(is_null($excess)){
                $this->tubeExcessModel->insertExcess(
                    $values['order_id'],
                    $values['quantity'],
                    $values['diameters'],
                );
                $this->flashMessage('Uložení proběhlo úspěšně', 'success');
            }else {
                $this->tubeExcessModel->updateExcess($values['order_id'], $values['quantity'], $values['diameters']);
                $this->flashMessage('Proběhlo upravení existujicího záznamu', 'success');
            }
            $this->redirect("CreateExcess:createExcess");
    }
    protected function createComponentEdittForm(): Form
    {
        $form = new Form;
        $form->addText('order_id', 'order_id')
            ->setDefaultValue('1234567');
        $form->addText('made_quantity', 'počet kusů')
            ->addRule($form::NUMERIC, 'Počet kusů se musí skládat pouze z číslic')
            ->addRule($form::MAX_LENGTH, 'Počet kusů může mít maximálně %d znaků', 4)
            ->setRequired('Vyplňte prosím %label');
        $diameter = $this->tubeDiameterModel->getDiameters();
        $form->addSelect('tube_diameter', 'Průměr: ', $diameter);
        $form->addSubmit('save', 'Uložit');
        $form->onSuccess[] = [$this, 'editFormSucceeded'];
        return $form;
    }
    public function editFormSucceeded(Form $form, array $values): void
    {
        $this->tubeExcessModel->updateExcess(
            $values['order_id'],
            $values['made_quantity'],
            $values['tube_diameter'],
        );

        $this->flashMessage('Zakázka byla upravena.', 'success');
        $this->redirect('CreateExcess:createExcess');
    }
}