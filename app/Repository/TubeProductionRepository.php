<?php

namespace App\Repository;

use Nette\Database\ResultSet;
use App\Exceptions;
use Nette;

class TubeProductionRepository extends BaseRepository
{
    CONST TABLE_NAME = 'tube_production';

    public function getTubeProduction(int $limit, int $offset)
    {
        return $this->database->query('
            SELECT order_id, tube_production.id, material_id, employee.name, employee.employee_id, diameter, made_quantity, DATE_FORMAT(create_date,"%d-%m-%Y") AS date_created, shift_name 
            FROM tube_production 
            INNER JOIN employee ON tube_production.employee_id = employee.employee_id 
            INNER JOIN shift ON tube_production.shift_id = shift.shift_id
            INNER JOIN tube_diameter ON tube_diameter.diameter_id = tube_production.diameter_id
            ORDER BY create_date DESC
			LIMIT ?
			OFFSET ?',$limit, $offset);
    }

    public function getCountAllProduction()
    {
        return $this->database->query('SELECT material_id FROM tube_production ORDER BY create_date DESC')->fetchAll();
    }

    public function getOrderById()
    {
        return $this->database->table(self::TABLE_NAME);
    }

    public function searchOrderByOrderId($material_id): ResultSet
    {
            return $this->database->query('
                SELECT material_id, employee.name, diameter, made_quantity, create_date, shift_name 
                FROM tube_production 
                INNER JOIN employee ON tube_production.employee_id = employee.employee_id 
                INNER JOIN shift ON tube_production.shift_id = shift.shift_id 
                INNER JOIN tube_diameter ON tube_diameter.diameter_id = tube_production.diameter_id
                WHERE material_id LIKE "%"?"%"
                ORDER BY create_date DESC', $material_id);
        }


    public function insertNewData($order_id, $material_id, $employee_id, $tube_diameter, $made_quantity, $shift_id)
    {

        try {
            $this->database->table(self::TABLE_NAME)->insert([
                'order_id' => $order_id,
                'material_id' => $material_id,
                'employee_id' => $employee_id,
                'diameter_id' => $tube_diameter,
                'made_quantity' => $made_quantity,
                'shift_id' => $shift_id,
            ]);
        }
        catch (Nette\Database\UniqueConstraintViolationException $e)
        {
            throw new Exceptions\DuplicateNameException();
        }
    }
    public function updateNewData($id , $material_id, $tube_diameter, $made_quantity, $shift_id)
    {
        $this->database->table(self::TABLE_NAME)
            ->where('id', $id)
            ->update([
            'material_id' => $material_id,
            'diameter_id' => $tube_diameter,
            'made_quantity' => $made_quantity,
            'shift_id' => $shift_id,
        ]);
    }
    public function getLastDiameter(): string{
        $last_diameter = '1';
        $tube = $this->database->table(self::TABLE_NAME);
        $tube->limit(1);
        $tube->order('create_date DESC');
        foreach ($tube as $tub) {
            $last_diameter = $tub->diameter_id;
        }
        return $last_diameter;
    }

    public function deleteRecord($id)
    {
        $this->database->query('DELETE FROM tube_production WHERE id = ?',$id);
    }

    public function getNoDupTubeProduction($limit, $offset)
    {
        $values = $this->database->query('
            SELECT order_id, tube_production.id, material_id, employee.name, employee.employee_id, diameter, made_quantity, DATE_FORMAT(create_date,"%d-%m-%Y") AS date_created, shift_name 
            FROM tube_production 
            INNER JOIN employee ON tube_production.employee_id = employee.employee_id 
            INNER JOIN shift ON tube_production.shift_id = shift.shift_id
            INNER JOIN tube_diameter ON tube_diameter.diameter_id = tube_production.diameter_id
            ORDER BY create_date DESC')->fetchAll();
        $current_material = 0;
        $new_values = [];
        $same_values = [];
        $result = [];
        $index_new = 0;
        $index_same = 0;
        $x = 0;
        foreach ($values as $i => $value)
        {

            if ($value->material_id != $current_material){
                $new_values[$index_new]['order_id'] = $value->order_id;
                $new_values[$index_new]['id'] = $value->id;
                $new_values[$index_new]['material_id'] = $value->material_id;
                $new_values[$index_new]['name'] = $value->name;
                $new_values[$index_new]['employee_id'] = $value->employee_id;
                $new_values[$index_new]['diameter'] = $value->diameter;
                $new_values[$index_new]['made_quantity'] = $value->made_quantity;
                $new_values[$index_new]['date_created'] = $value->date_created;
                $new_values[$index_new]['shift_name'] = $value->shift_name;
                $index_new += 1;

            }
            else{

                $same_values[$index_new][$x]['order_id'] = $value->order_id;
                $same_values[$index_new][$x]['id'] = $value->id;
                $same_values[$index_new][$x]['material_id'] = $value->material_id;
                $same_values[$index_new][$x]['name'] = $value->name;
                $same_values[$index_new][$x]['employee_id'] = $value->employee_id;
                $same_values[$index_new][$x]['diameter'] = $value->diameter;
                $same_values[$index_new][$x]['made_quantity'] = $value->made_quantity;
                $same_values[$index_new][$x]['date_created'] = $value->date_created;
                $same_values[$index_new][$x]['shift_name'] = $value->shift_name;
                $index_same += 1;
                $x += 1;
            }
            $current_material = $value->material_id;
        }
        foreach (range(($offset), $offset + $limit - 1) as $i){
            $result[$i]['order_id'] = $new_values[$i]['order_id'];
            $result[$i]['id'] = $new_values[$i]['id'];
            $result[$i]['material_id'] = $new_values[$i]['material_id'];
            $result[$i]['name'] = $new_values[$i]['name'];
            $result[$i]['employee_id'] = $new_values[$i]['employee_id'];
            $result[$i]['diameter'] = $new_values[$i]['diameter'];
            $result[$i]['made_quantity'] = $new_values[$i]['made_quantity'];
            $result[$i]['date_created'] = $new_values[$i]['date_created'];
            $result[$i]['shift_name'] = $new_values[$i]['shift_name'];
        }

        return array($result, $same_values);
    }

}