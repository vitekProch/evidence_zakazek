<?php

namespace App\Repository;

use Nette\Database\ResultSet;

class TubeProductionRepository extends BaseRepository
{
    public function getTubeProduction(): ResultSet
    {
        return $this->database->query('
            SELECT tube_production.id, order_id, employee.name, diameter, made_quantity, create_date, shift_name 
            FROM tube_production 
            INNER JOIN employee ON tube_production.employee_id = employee.employee_id 
            INNER JOIN shift ON tube_production.shift_id = shift.shift_id
            INNER JOIN tube_diameter ON tube_diameter.diameter_id = tube_production.tube_diameter
            ORDER BY create_date DESC ');
    }

    public function getOrderById()
    {
        return $this->database->table('tube_production');
    }

    public function searchOrderByOrderId($order_id): ResultSet
    {
            return $this->database->query('
                SELECT order_id, employee.name, diameter, made_quantity, create_date, shift_name 
                FROM tube_production 
                INNER JOIN employee ON tube_production.employee_id = employee.employee_id 
                INNER JOIN shift ON tube_production.shift_id = shift.shift_id 
                INNER JOIN tube_diameter ON tube_diameter.diameter_id = tube_production.tube_diameter
                WHERE order_id LIKE "%"?"%"
                ORDER BY create_date DESC', $order_id);
        }


    public function insertNewData($order_id, $employee_id, $tube_diameter, $made_quantity, $shift_id)
    {
        $this->database->table('tube_production')->insert([
            'order_id' => $order_id,
            'employee_id' => $employee_id,
            'tube_diameter' => $tube_diameter,
            'made_quantity' => $made_quantity,
            'shift_id' => $shift_id,
        ]);
    }
    public function updateNewData($id , $order_id, $tube_diameter, $made_quantity, $shift_id)
    {
        $this->database->table('tube_production')
            ->where('id', $id)
            ->update([
            'order_id' => $order_id,
            'tube_diameter' => $tube_diameter,
            'made_quantity' => $made_quantity,
            'shift_id' => $shift_id,
        ]);
    }
    public function getLastDiameter(): string{
        $last_diameter = '1';
        $tube = $this->database->table('tube_production');
        $tube->limit(1);
        $tube->order('create_date DESC');
        foreach ($tube as $tub) {
            $last_diameter = $tub->tube_diameter;
        }
        bdump($last_diameter);
        return $last_diameter;
    }
    public function getSpecificDiameter($id): string{
        $specificDiameter = '1';
        $tube = $this->database->table('tube_production');
        $tube->where('id', $id);
        foreach ($tube as $tub) {
            $specificDiameter = $tub->tube_diameter;
        }
        return $specificDiameter;
    }
    public function getSpecificShift($id): int{
        $specificShift = "1";
        $tube = $this->database->table('tube_production');
        $tube->where('id', $id);
        foreach ($tube as $tub) {
            $specificShift = $tub->shift_id;
        }
        return $specificShift;
    }
}