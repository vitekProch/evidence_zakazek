<?php

namespace App\Model;

use App\Repository\TubeProductionRepository;
use Nette\Database\ResultSet;

class TubeProductionModel
{
    /**
     * @var TubeProductionRepository
     * @inject
     */
    public TubeProductionRepository $tubeProductionRepository;

    public function __construct(TubeProductionRepository $tubeProductionRepository)
    {
        $this->tubeProductionRepository = $tubeProductionRepository;
    }


    public function getTubeProduction(int $limit, int $offset): array
    {
        $order_counter = -1;
        $more_value = [];
        $one_value = [];
        $counter = 0;
        $counter_VAR = 0;
        $current = "";
        $check = FALSE;
        $var = "";
        $value = $this->tubeProductionRepository->getTubeProduction(($this->getCountAllProductionTst($limit)), $offset);
        foreach ($value as $val)
        {
            if ($val->material_id == $current){
                $more_value[$counter][$counter_VAR] = $var;
                $counter_VAR += 1;
                $order_counter -= 1;
                $check = TRUE;
            }
            if ($val->material_id != $current){
                if ($check){
                    $more_value[$counter][$counter_VAR] = $var;
                }
                if(!$check){
                    $one_value[$counter][0] = $var;
                }
                $counter += 1;
                $counter_VAR = 0;
                $check = FALSE;
            }
            $var = $val;
            $current = $val->material_id;
            $order_counter += 1;
            if ($order_counter == $limit){
                break;
            }
        }
        $pole = $more_value + $one_value;
        ksort($pole);

        return $pole;
    }

    public function getCountAllProductionTst($limit = "NULL")
    {
        $cnt = 0;
        $counter = 0;
        $current_material_id = "";
        $material = $this->tubeProductionRepository->getCountAllProduction();
        foreach ($material as $material_id) {

            if ($limit == $counter) {
                break;
            }
            if ($material_id->material_id == $current_material_id) {
                $counter -= 1;
            }
            $current_material_id = $material_id->material_id;
            $counter += 1;
            $cnt += 1;
        }

        return $cnt;
    }


    public function getCountAllProduction($limit = "NULL")
    {
        $counter = 0;
        $current_material_id = "";
        $material = $this->tubeProductionRepository->getCountAllProduction();
        foreach ($material as $material_id){
            if ($limit == $counter){
                break;
            }
            if ($material_id->material_id == $current_material_id){
                $counter -= 1;
            }
            $current_material_id = $material_id->material_id;
            $counter += 1;
        }
        return $counter;
    }

    public function insertNewData($order_id, $material_id, $employee_id, $tube_diameter, $made_quantity, $shift_id)
    {
        $this->tubeProductionRepository->insertNewData($order_id, $material_id, $employee_id, $tube_diameter, $made_quantity, $shift_id);
    }

    public function searchOrderByMaterialId($order_id): ResultSet
    {
        return $this->tubeProductionRepository->searchOrderByMaterialId($order_id);
    }

    public function searchOrderByOrderId($order_id): ResultSet
    {
        return $this->tubeProductionRepository->searchOrderByOrderId($order_id);
    }

    public function getLastDiameter(): string
    {
        return $this->tubeProductionRepository->getLastDiameter();
    }

    public function getOrderById()
    {
        return $this->tubeProductionRepository->getOrderById();
    }

    public function updateNewData($id, $order_id, $tube_diameter, $made_quantity, $shift_id)
    {
        $this->tubeProductionRepository->updateNewData($id, $order_id, $tube_diameter, $made_quantity, $shift_id);
    }
    public function deleteRecord($id)
    {
        $this->tubeProductionRepository->deleteRecord($id);
    }

    public function getNoDupTubeProduction($limit, $offset)
    {
        $orders = $this->tubeProductionRepository->getNoDupTubeProduction();

        $current_material = 0;
        $new_values = [];
        $same_material = [];
        $displayed_order = [];
        $index_new = 0;
        $index_material = 0;

        foreach ($orders as $order)
        {

            if ($order->material_id != $current_material){
                $new_values[$index_new]['order_id'] = $order->order_id;
                $new_values[$index_new]['id'] = $order->id;
                $new_values[$index_new]['material_id'] = $order->material_id;
                $new_values[$index_new]['name'] = $order->name;
                $new_values[$index_new]['employee_id'] = $order->employee_id;
                $new_values[$index_new]['diameter'] = $order->diameter;
                $new_values[$index_new]['made_quantity'] = $order->made_quantity;
                $new_values[$index_new]['date_created'] = $order->date_created;
                $new_values[$index_new]['shift_name'] = $order->shift_name;
                $index_new += 1;

            }
            else{

                $same_material[$index_new][$index_material]['order_id'] = $order->order_id;
                $same_material[$index_new][$index_material]['id'] = $order->id;
                $same_material[$index_new][$index_material]['material_id'] = $order->material_id;
                $same_material[$index_new][$index_material]['name'] = $order->name;
                $same_material[$index_new][$index_material]['employee_id'] = $order->employee_id;
                $same_material[$index_new][$index_material]['diameter'] = $order->diameter;
                $same_material[$index_new][$index_material]['made_quantity'] = $order->made_quantity;
                $same_material[$index_new][$index_material]['date_created'] = $order->date_created;
                $same_material[$index_new][$index_material]['shift_name'] = $order->shift_name;
                $index_material += 1;
            }
            $current_material = $order->material_id;
        }

        //   PAGINATION   //

        foreach (range(($offset), $offset + $limit - 1) as $i){
            $displayed_order[$i]['order_id'] = $new_values[$i]['order_id'];
            $displayed_order[$i]['id'] = $new_values[$i]['id'];
            $displayed_order[$i]['material_id'] = $new_values[$i]['material_id'];
            $displayed_order[$i]['name'] = $new_values[$i]['name'];
            $displayed_order[$i]['employee_id'] = $new_values[$i]['employee_id'];
            $displayed_order[$i]['diameter'] = $new_values[$i]['diameter'];
            $displayed_order[$i]['made_quantity'] = $new_values[$i]['made_quantity'];
            $displayed_order[$i]['date_created'] = $new_values[$i]['date_created'];
            $displayed_order[$i]['shift_name'] = $new_values[$i]['shift_name'];
        }

        return array($displayed_order, $same_material);
    }
}