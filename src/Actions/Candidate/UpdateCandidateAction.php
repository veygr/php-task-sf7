<?php

declare(strict_types=1);

namespace App\Actions\Candidate;

use App\Entity\Candidate;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UpdateCandidateAction {
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function execute(
        Candidate $project
    ): void {
        $this->em->persist($project);
        $this->em->flush();
    }
}
