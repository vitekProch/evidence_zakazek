<?php

namespace App\Model;

use App\Repository\ShiftRepository;

class ShiftModel
{
    /**
     * @var ShiftRepository
     * @inject
     */
    public ShiftRepository $shiftRepository;

    public function getShiftById($shift_id)
    {
        return $this->shiftRepository->getShiftById($shift_id);
    }

}