<?php

namespace App\Presenters;

class ExcessPresenter extends HomepagePresenter
{
    public function beforeRender()
    {
        parent::beforeRender();

        $this->searchExcess();
        $this->updateExcess();

    }

    public function searchExcess()
    {
        if(isset($_POST['input'])){
            $input =  $_POST['input'];
            $data = $this->tubeExcessModel->findExcess($input);
            $this->template->data = $data;
        }
    }
    public function updateExcess()
    {
        if(isset($_POST['quantityUpdate'])){
            $quantitySend = $_POST['quantityUpdate'];
            $orderId = $_POST['orderId'];
            $this->tubeExcessModel->newExcessQuantity($orderId, $quantitySend);
            $this->flashMessage('Proběhlo upravení existujicího záznamu', 'success');
        }
    }
}