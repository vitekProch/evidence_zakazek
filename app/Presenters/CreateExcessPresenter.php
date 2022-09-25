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

    protected function createComponentExcessForm(): Form
    {
        $form = new Form();
        $form->addText('material_id', 'Číslo zakázky:')
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
            $excess = $this->tubeExcessModel->checkExcess($values['material_id']);
            if(is_null($excess)){
                $this->tubeExcessModel->insertExcess(
                    $values['material_id'],
                    $values['quantity'],
                    $values['diameters'],
                );
                $this->flashMessage('Uložení proběhlo úspěšně', 'success');
            }else {
                $this->tubeExcessModel->updateExcess($values['material_id'], $values['quantity'], $values['diameters']);
                $this->flashMessage('Proběhlo upravení existujicího záznamu', 'success');
            }
            $this->redirect("CreateExcess:createExcess");
    }

    protected function createComponentFormEdit($name) {

        $form = new Form($this, $name);
        $form->addText('material_id', 'číslo materiálu')
            ->addRule($form::LENGTH, 'Číslo materuálu může být krátné minimálně 6 číslic',[6,7])
            ->addRule($form::NUMERIC, 'Číslo materuálu se musí skládat pouze z číslic')
            ->setRequired('Vyplňte prosím %label');

        $diameter = $this->tubeDiameterModel->getDiameters();
        $form->addHidden('id');
        $form->addSelect('diameter_id', 'Průměr: ', $diameter);

        $form->addText('quantity', 'počet kusů')
            ->addRule($form::NUMERIC, 'Počet kusů se musí skládat pouze z číslic')
            ->addRule($form::MAX_LENGTH, 'Počet kusů může mít maximálně %d znaků', 4)
            ->setRequired('Vyplňte prosím %label');

        $form->addSubmit('ok', 'Upravit')
            ->setAttribute('class', 'btn btn-primary smaller');
        $form->onSuccess[] = array($this, 'formSubmitted');

        return $form;
    }


    public function formSubmitted($dat) {
        $val = $dat->getForm()->getValues();
        $this->tubeExcessModel->newExcess($val['id'], $val['material_id'], $val['quantity'], $val['diameter_id']);

        $this->flashMessage('Úprava záznamu byla úspěsná', 'success');


    }
    public function handleDelete(int $id)
    {
        $this->tubeExcessModel->deleteExcess($id);
        $this->flashMessage('Zakázka byla odstraněna.', 'success');
        $this->redirect('CreateExcess:createExcess');
    }

    public function handleEditovat($material_id){
        $order_value = $this->tubeExcessModel->getExcessById($material_id);
        if (!$order_value) {
            $this->error('Materiál navíc nebyl nalezen');
        }
        $this['formEdit']->setDefaults($order_value);


        if ($this->isAjax()) {
            $this->payload->isModal = TRUE;
            $this->redrawControl("modal");

        }
    }
}