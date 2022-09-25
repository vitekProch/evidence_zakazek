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

    public function __construct(ShiftRepository $shiftRepository)
    {
        $this->shiftRepository = $shiftRepository;
    }

    public function getShiftById($shift_id)
    {
        return $this->shiftRepository->getShiftById($shift_id);
    }

    public function getShiftByName($shift_name)
    {
        return $this->shiftRepository->getShiftByName($shift_name);
    }
}
