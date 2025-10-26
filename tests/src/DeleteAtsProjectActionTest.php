<?php

namespace App\Tests\src;

use App\Actions\AtsProject\DeleteAtsProjectAction;
use App\Actions\SendEmailAction;
use App\Entity\AtsProject;
use App\Entity\Candidate;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class DeleteAtsProjectActionTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private SendEmailAction $sendEmailAction;
    private DeleteAtsProjectAction $deleteAtsProjectAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->sendEmailAction = $this->createMock(SendEmailAction::class);

        $this->deleteAtsProjectAction = new DeleteAtsProjectAction(
            $this->entityManager,
            $this->sendEmailAction
        );
    }

    public function testDeleteAtsProjectAction(): void
    {
        $this->entityManager->expects(self::exactly(3))
            ->method('remove');

        $atsProject = new AtsProject();
        $atsProject->setName('test');

        $candidate1 = new Candidate();
        $candidate2 = new Candidate();

        $atsProject->addCandidate($candidate1);
        $atsProject->addCandidate($candidate2);

        $this->deleteAtsProjectAction->execute($atsProject);
    }
}
