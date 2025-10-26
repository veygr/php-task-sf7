<?php

namespace App\Actions\AtsProject;

use App\Actions\SendEmailAction;
use App\Entity\AtsProject;
use Doctrine\ORM\EntityManagerInterface;

readonly class DeleteAtsProjectAction
{
    public function __construct(
        private EntityManagerInterface $em,
        private SendEmailAction        $sendEmailAction
    ) {
    }

    public function execute(
        AtsProject $project
    ): void {
        foreach ($project->getCandidates() as $candidate) {
            $this->em->remove($candidate);
        }
        $this->em->remove($project);
        $this->em->flush();

        $this->sendNotification($project);
    }

    private function sendNotification(AtsProject $project): void
    {
        if(!$project->getManager()) {
            return;
        }

        $this->sendEmailAction->execute(
            $project->getManager()->getEmail(),
            'ATS project was deleted',
            'ATS project was deleted: '. $project->getName()
        );
    }
}
