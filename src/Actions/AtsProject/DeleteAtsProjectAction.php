<?php

declare(strict_types=1);

namespace App\Actions\AtsProject;

use App\Actions\SendEmailAction;
use App\Entity\AtsProject;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DeleteAtsProjectAction
{
    public function __construct(
        private EntityManagerInterface $em,
        private SendEmailAction $sendEmailAction
    ) {
    }

    public function execute(AtsProject $project): void
    {
        $this->removeCandidates($project);

        $this->em->remove($project);
        $this->em->flush();

        $this->sendNotificationToManager($project);
    }

    private function removeCandidates(AtsProject $project): void
    {
        foreach ($project->getCandidates() as $candidate) {
            $this->em->remove($candidate);
        }
    }

    private function sendNotificationToManager(AtsProject $project): void
    {
        $manager = $project->getManager();

        if ($manager === null) {
            return;
        }

        $this->sendEmailAction->execute(
            $manager->getEmail(),
            'ATS project was deleted',
            sprintf('ATS project was deleted: %s', $project->getName())
        );
    }
}
