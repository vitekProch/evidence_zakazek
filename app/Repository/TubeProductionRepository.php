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

    public function searchOrderByMaterialId($material_id): ResultSet
    {
            return $this->database->query('
                SELECT order_id, material_id, employee.name, diameter, made_quantity, create_date, shift_name 
                FROM tube_production 
                INNER JOIN employee ON tube_production.employee_id = employee.employee_id 
                INNER JOIN shift ON tube_production.shift_id = shift.shift_id 
                INNER JOIN tube_diameter ON tube_diameter.diameter_id = tube_production.diameter_id
                WHERE material_id LIKE "%"?"%"
                ORDER BY create_date DESC', $material_id);
    }

    public function searchOrderByOrderId($order_id): ResultSet
    {
        return $this->database->query('
                SELECT order_id, material_id, employee.name, diameter, made_quantity, create_date, shift_name 
                FROM tube_production 
                INNER JOIN employee ON tube_production.employee_id = employee.employee_id 
                INNER JOIN shift ON tube_production.shift_id = shift.shift_id 
                INNER JOIN tube_diameter ON tube_diameter.diameter_id = tube_production.diameter_id
                WHERE order_id = ?', $order_id);
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

    public function getNoDupTubeProduction()
    {
        return $this->database->query('
            SELECT order_id, tube_production.id, material_id, employee.name, employee.employee_id, diameter, made_quantity, DATE_FORMAT(create_date,"%d-%m-%Y") AS date_created, shift_name 
            FROM tube_production 
            INNER JOIN employee ON tube_production.employee_id = employee.employee_id 
            INNER JOIN shift ON tube_production.shift_id = shift.shift_id
            INNER JOIN tube_diameter ON tube_diameter.diameter_id = tube_production.diameter_id
            ORDER BY create_date DESC')->fetchAll();
    }

}