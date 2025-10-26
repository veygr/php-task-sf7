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
        $project = $candidate->getAtsProject();
        if (empty($project) || !$project->isActive()) {
            throw new CreateProjectNotActiveException();
        }

        $this->em->persist($candidate);
        $this->em->flush();

        $this->sendNotification($candidate);
    }

    private function sendNotification(Candidate $candidate): void
    {
        if (!$candidate->getAtsProject()->getManager()) {
            return;
        }

        $this->sendEmailAction->execute(
            $candidate->getAtsProject()->getManager()->getEmail(),
            'New candidate in ATS project',
            'New candidate in : ' . $candidate->getAtsProject()->getName() . ' - ' . $candidate->getFirstName() . ' ' . $candidate->getLastName(),
        );
    }
}
