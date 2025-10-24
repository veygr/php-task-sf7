<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Entity\User;
use App\Exceptions\User\UserNotFoundException;
use App\Repository\UserRepository;

class FindUserByEmailAction
{
    public function __construct(
        private UserRepository $userRepository
    )
    {
    }

    /**
     * @throws UserNotFoundException
     */
    public function execute(string $email): User
    {
        return
            $this->userRepository->findOneBy(['email' => $email])
            ?? throw new UserNotFoundException();
    }
}
