<?php

declare(strict_types=1);

namespace App\Actions\AtsProject;

use App\Actions\SendEmailAction;
use App\Entity\AtsProject;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CreateAtsProjectAction {
    public function __construct(
        private EntityManagerInterface $em,
        private SendEmailAction $sendEmailAction
    ) {
    }

    public function execute(AtsProject $project): void
    {
        $project->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($project);
        $this->em->flush();

        $this->sendNotificationToManager($project);
    }

    private function sendNotificationToManager(AtsProject $project): void
    {
        $manager = $project->getManager();

        if ($manager === null) {
            return;
        }

        $this->sendEmailAction->execute(
            $manager->getEmail(),
            'New ATS project created',
            sprintf('New ATS project created: %s', $project->getName())
        );
    }
}
