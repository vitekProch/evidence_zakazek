<?php

namespace App\Repository;
use Nette;

class BaseRepository
{
    use Nette\SmartObject;

    /** @var Nette\Database\Context */
    protected $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }
}