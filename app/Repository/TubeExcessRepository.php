<?php

namespace App\Repository;

class TubeExcessRepository extends BaseRepository
{
    public function findExcess($order_id)
    {
        return $this->database->query('SELECT * FROM tube_excess WHERE order_id = ?', $order_id)->fetch();
    }
    public function insertExcess($order_id, $quantity)
    {
        $this->database->table('tube_excess')->insert([
            'order_id' => $order_id,
            'quantity' => $quantity,
        ]);
    }

    public function updateExcess($order_id, $quantity)
    {
        $this->database->query('
            update tube_excess
            set quantity = quantity + ?
            WHERE order_id = ?', $quantity, $order_id);
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

    public function checkExcess($order_id)
    {
        return $this->database->query('SELECT order_id FROM tube_excess WHERE order_id = ?', $order_id)->fetch();
    }
}