<?php

namespace App\Repository;

class ShiftRepository extends BaseRepository
{
    public function getShiftById($shift_id)
    {
        return $this->database->query('SELECT shift_name FROM shift WHERE shift_id = ?', $shift_id);
    }
}