<?php


namespace App\Presenters;
use Nette\Application\UI\Form;

class SearchPresenter extends BasePresenter
{
    protected function createComponentSearchForm()
    {
        $form = new Form;
        $form->addText('search_value', 'Send:')
            ->setRequired(TRUE);
        $form->addSubmit('send', 'Send');
        $form->onSuccess[] = [$this, 'searchFormSucceeded'];
        return $form;
    }
    public function searchFormSucceeded(Form $form, $values): void
    {
        $this->redirect("Search:search", [$values->search_value]);
    }
    public function actionSearch($search_value)
    {
        $tube_production = $this->tubeProductionModel->searchOrderByOrderId($search_value);
        $this->template->tube_production = $tube_production;
    }
}