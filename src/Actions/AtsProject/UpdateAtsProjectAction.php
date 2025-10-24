<?php

declare(strict_types=1);

namespace App\Actions\AtsProject;

use App\Entity\AtsProject;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UpdateAtsProjectAction {
    public function __construct(
        private EntityManagerInterface $em
    ) {
        //
    }

    public function execute(
        AtsProject $project
    ): void {
        $project->setUpdatedAt(new \DateTimeImmutable());

        $this->em->persist($project);
        $this->em->flush();
    }
}
