<?php

namespace App\Model;

use App\Repository\EmployeeRepository;

class EmployeeModel
{
    /**
     * @var EmployeeRepository
     * @inject
     */
    public $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }
    public function insertShift($shift_id, $employee_id)
    {
        $this->employeeRepository->insertShift($shift_id, $employee_id);
    }

    public function getShift($employee_id)
    {
        return $this->employeeRepository->getShift($employee_id);
    }
}