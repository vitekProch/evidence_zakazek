<?php

namespace App\Repository;

use Nette\Database\ResultSet;
use Nette\Database\Row;

class TubeExcessRepository extends BaseRepository
{
    public function getExcess(): ResultSet
    {
        return $this->database->query('SELECT order_id,quantity,diameter FROM `tube_excess` INNER JOIN tube_diameter ON tube_excess.diameter_id = tube_diameter.diameter_id ORDER BY tube_diameter.diameter_id');
    }

    public function findExcess($order_id): ?Row
    {
        return $this->database->query('SELECT order_id, quantity, diameter FROM tube_excess INNER JOIN tube_diameter ON tube_excess.diameter_id = tube_diameter.diameter_id WHERE order_id = ?', $order_id)->fetch();
    }
    public function insertExcess($order_id, $quantity, $diameter_excess)
    {
        $this->database->table('tube_excess')->insert([
            'order_id' => $order_id,
            'quantity' => $quantity,
            'diameter_id' => $diameter_excess,
        ]);
    }

    public function updateExcess($order_id, $quantity, $diameters)
    {
        $this->database->query('
            update tube_excess
            set quantity = quantity + ?, diameter_id = ?
            WHERE order_id = ?', $quantity, $diameters, $order_id);
    }
    public function deleteExcess($excess_id)
    {
        $this->database->query('DELETE FROM tube_excess WHERE order_id = ?',$excess_id);
    }

    public function newExcessQuantity($order_id, $quantity)
    {
        $this->database->query('
            update tube_excess
            set quantity = ?
            WHERE order_id = ?', $quantity, $order_id);
    }

    public function checkExcess($order_id): ?Row
    {
        return $this->database->query('SELECT order_id FROM tube_excess WHERE order_id = ?', $order_id)->fetch();
    }
    public function getExcessByOrderId()
    {
        return $this->database->table('tube_excess');
    }
}