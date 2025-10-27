<?php

namespace App\Exceptions\Candidate;

class CreateProjectNotActiveException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Cannot create candidate. The ATS project is not active.', 400);
    }
}
