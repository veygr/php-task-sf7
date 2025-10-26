<?php

namespace App\Tests\src;

use App\Actions\Candidate\CreateCandidateAction;
use App\Actions\SendEmailAction;
use App\Entity\AtsProject;
use App\Entity\Candidate;
use App\Entity\User;
use App\Exceptions\Candidate\CreateProjectNotActiveException;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CreateCandidateActionTest extends TestCase
{

    private EntityManagerInterface $entityManager;
    private SendEmailAction $sendEmailAction;
    private CreateCandidateAction $createCandidateAction;
    private Candidate $candidate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->sendEmailAction = $this->createMock(SendEmailAction::class);

        $this->createCandidateAction = new CreateCandidateAction(
            $this->entityManager,
            $this->sendEmailAction
        );

        $manager = new User();
        $manager->setEmail('manager@example.com');

        $atsProject = new AtsProject();
        $atsProject->setName('New Project');
        $atsProject->setActive(true);
        $atsProject->setManager($manager);

        $this->candidate = new Candidate();
        $this->candidate->setFirstName('John');
        $this->candidate->setLastName('Doe');
        $this->candidate->setEmail('candidate@example.com');
        $this->candidate->setAtsProject($atsProject);
    }

    public function testCreateCandidateAction(): void
    {
        $this->entityManager->expects(self::once())
            ->method('persist')
            ->with($this->candidate);

        $this->entityManager->expects(self::once())
            ->method('flush');

        $this->sendEmailAction->expects(self::once())
            ->method('execute')
            ->with('manager@example.com', 'New candidate in ATS project', 'New candidate in : New Project - John Doe');

        $this->createCandidateAction->execute($this->candidate);
    }

    public function testCreateCandidateNoActiveProject():void
    {
        $this->candidate->getAtsProject()->setActive(false);

        $this->expectException(CreateProjectNotActiveException::class);

        $this->createCandidateAction->execute($this->candidate);
    }
}
