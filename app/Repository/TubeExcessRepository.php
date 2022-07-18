<?php

namespace App\Repository;

class TubeExcessRepository extends BaseRepository
{
    public function findExcess($order_id)
    {
        return $this->database->query('SELECT * FROM tube_excess WHERE order_id = ?', $order_id)->fetch();
    }
}