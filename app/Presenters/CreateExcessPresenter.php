<?php

namespace App\Presenters;

use Nette\Application\UI\Form;

class CreateExcessPresenter extends BasePresenter
{
    protected function createComponentExcessForm()
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

        $form->addSubmit('save', 'Uložit');
        $form->onSuccess[] = [$this, 'ExcessFormSucceeded'];
        return $form;
    }
    public function ExcessFormSucceeded($form, array $values): void
    {
        $post = $this->tubeExcessModel->checkExcess($values['order_id']);
        if(is_null($post)){
            $this->tubeExcessModel->insertExcess(
                $values['order_id'],
                $values['quantity'],
            );
            $this->flashMessage('Uložení proběhlo úspěšně', 'success');
        }else {
            $this->tubeExcessModel->updateExcess($values['order_id'], $values['quantity']);
            $this->flashMessage('Proběhlo upravení existujicího záznamu', 'success');
        }
        $this->redirect('TubeProduction:production');
    }
}