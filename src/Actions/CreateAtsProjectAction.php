<?php

declare(strict_types=1);

namespace App\Actions;

use App\Entity\AtsProject;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CreateAtsProjectAction {
    public function __construct(
        private EntityManagerInterface $em,
        private SendEmailAction $sendEmailAction
    ) {
        //
    }

    public function execute(
        AtsProject $project
    ): void {
        $project->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($project);
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
            'New ATS project created',
            'New ATS project created: '. $project->getName()
        );
    }
}
