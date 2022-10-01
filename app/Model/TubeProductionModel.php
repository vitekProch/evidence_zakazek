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
        return $this->tubeProductionRepository->getNoDupTubeProduction($limit, $offset);
    }
}