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
        $form->getElementPrototype()->class = 'ajax';
        $form->setMethod('POST');
        $form->addText('order_id', 'Číslo zakázky:')
            ->addRule($form::LENGTH, 'Číslo zakázky může být krátné minimálně 6 číslic',[6,7])
            ->addRule($form::NUMERIC, 'Číslo zakázky se musí skládat pouze z číslic')
            ->setRequired('Vyplňte prosím %label');

        $form->addText('quantity', 'Počet navíc:')
            ->setRequired('Vyplňte prosím %label')
            ->addRule($form::NUMERIC, 'Počet kusů se musí skládat pouze z číslic')
            ->addRule($form::MAX_LENGTH, 'Počet kusů může mít maximálně %d znaků', 4);

        $form->addSubmit('save', 'Uložit')
            ->onClick[] = [$this, 'send'];
        return $form;
    }

    public function send(SubmitButton $button) {
        $values = $button->getForm()->getValues();
        bdump($this->isAjax());
        if ($this->isAjax()) {
            $excess = $this->tubeExcessModel->checkExcess($values['order_id']);
            if(is_null($excess)){
                $this->tubeExcessModel->insertExcess(
                    $values['order_id'],
                    $values['quantity'],
                );
                $this->flashMessage('Uložení proběhlo úspěšně', 'success');
            }else {
                $this->tubeExcessModel->updateExcess($values['order_id'], $values['quantity']);
                $this->flashMessage('Proběhlo upravení existujicího záznamu', 'success');
            }
        } else {
            $this->redirect('this');
        }
    }

}