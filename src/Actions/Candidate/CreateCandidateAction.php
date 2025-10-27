<?php

declare(strict_types=1);

namespace App\Actions\Candidate;

use App\Actions\SendEmailAction;
use App\Entity\Candidate;
use App\Exceptions\Candidate\CreateProjectNotActiveException;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CreateCandidateAction
{
    public function __construct(
        private EntityManagerInterface $em,
        private SendEmailAction        $sendEmailAction,
    )
    {
    }

    /**
     * @throws CreateProjectNotActiveException
     */
    public function execute(
        Candidate $candidate,
    ): void
    {
        $this->validateProject($candidate);

        $this->em->persist($candidate);
        $this->em->flush();

        $this->sendNotificationToProjectManager($candidate);
    }

    /**
     * @throws CreateProjectNotActiveException
     */
    private function validateProject(Candidate $candidate): void
    {
        $project = $candidate->getAtsProject();

        if ($project === null || !$project->isActive()) {
            throw new CreateProjectNotActiveException();
        }
    }

    private function sendNotificationToProjectManager(Candidate $candidate): void
    {
        $project = $candidate->getAtsProject();
        $manager = $project?->getManager();

        if ($manager === null) {
            return;
        }

        $this->sendEmailAction->execute(
            $manager->getEmail(),
            'New candidate in ATS project',
            sprintf(
                'New candidate in: %s - %s %s',
                $project->getName(),
                $candidate->getFirstName(),
                $candidate->getLastName()
            )
        );
    }
}
