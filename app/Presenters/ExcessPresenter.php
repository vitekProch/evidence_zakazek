<?php

namespace App\Presenters;

class ExcessPresenter extends HomepagePresenter
{
    public function beforeRender()
    {
        parent::beforeRender();
        $input = $_POST['input'];
        $data = $this->tubeExcessModel->findExcess($input);
        if (!is_null($data)){
            $dates = array(
                "order_id" => $data->order_id,
                "made_quantity" => $data->quantity,
            );
            $this->renderExcess($dates);
        }

    }

    public function renderExcess($data)
    {
        $this->template->data = $data;
    }
}