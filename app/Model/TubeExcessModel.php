<?php

namespace App\Model;
use App\Repository\TubeExcessRepository;

class TubeExcessModel
{
    /**
     * @var TubeExcessRepository
     * @inject
     */
    public TubeExcessRepository $tubeExcessRepository;

    public function __construct(TubeExcessRepository $tubeExcessRepository)
    {
        $this->tubeExcessRepository = $tubeExcessRepository;
    }

    public function findExcess($order_id)
    {
        return $this->tubeExcessRepository->findExcess($order_id);
    }

}