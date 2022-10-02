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
        $options =  [
            "0" => "Číslo zakázky",
            "1" => "Číslo materiálu",
        ];
        $form->addSelect('search_select', 'Search', $options);
        $form->onSuccess[] = [$this, 'searchFormSucceeded'];
        return $form;
    }
    public function searchFormSucceeded(Form $form, $values): void
    {
        $this->redirect("Search:search", [$values->search_value], $values->search_select);
    }
    public function actionSearch(array $search_value, int $option)
    {
        if ($option == 1){
            $tube_production = $this->tubeProductionModel->searchOrderByMaterialId($search_value);
        }
        else{
            $tube_production = $this->tubeProductionModel->searchOrderByOrderId($search_value);
        }
        $this->template->tube_production = $tube_production;
    }
}