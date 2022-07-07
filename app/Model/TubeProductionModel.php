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

    public function getTubeProduction(): ResultSet
    {
        return $this->tubeProductionRepository->getTubeProduction();
    }

    public function insertNewData($order_id, $employee_id, $tube_diameter, $made_quantity, $shift_id)
    {
        $this->tubeProductionRepository->insertNewData($order_id, $employee_id, $tube_diameter, $made_quantity, $shift_id);
    }
    public function searchOrderByOrderId($order_id): ResultSet
    {
        return $this->tubeProductionRepository->searchOrderByOrderId($order_id);
    }
    public function getLastDiameter(): string
    {
        return $this->tubeProductionRepository->getLastDiameter();
    }
}