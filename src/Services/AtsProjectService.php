<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\AtsProject\CreateAtsProjectAction;
use App\Actions\User\FindUserByEmailAction;
use App\Entity\AtsProject;
use App\Exceptions\User\UserNotFoundException;

class AtsProjectService
{
    public function __construct(
        private readonly FindUserByEmailAction $findUserByEmailAction,
        private readonly CreateAtsProjectAction $createAtsProjectAction,
    )
    {

    }

    /**
     * @throws UserNotFoundException
     */
    public function addByNameAndEmail(string $name, string $email): void
    {
        $user = $this->findUserByEmailAction->execute($email);

        $atsProject = new AtsProject();
        $atsProject->setName($name);
        $atsProject->setManager($user);
        $atsProject->setActive(true);

        $this->createAtsProjectAction->execute($atsProject);
    }
}
