<?php


namespace App\Presenters;


class SearchPresenter extends BasePresenter
{
    public function actionSearch(array $search_value, int $option)
    {
        if ($option == 1){
            $tube_production = $this->tubeProductionModel->searchOrderByMaterialId($search_value)->fetchAll();
        }
        else{
            $tube_production = $this->tubeProductionModel->searchOrderByOrderId($search_value);
        }
        $this->template->tube_production = $tube_production;
    }
}