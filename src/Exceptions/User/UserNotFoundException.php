<?php

declare(strict_types=1);

namespace App\Exceptions\User;

class UserNotFoundException extends \Exception
{
    public function __construct(string $email)
    {
        parent::__construct(sprintf('User with email "%s" not found.', $email), 404);
    }
}
