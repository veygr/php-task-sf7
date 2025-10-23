<?php

declare(strict_types=1);

namespace App\Tests\src;

use App\Actions\CreateAtsProjectAction;
use App\Actions\SendEmailAction;
use App\Entity\AtsProject;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CreateAtsProjectActionTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private SendEmailAction $sendEmailAction;
    private CreateAtsProjectAction $createAtsProjectAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->sendEmailAction = $this->createMock(SendEmailAction::class);

        $this->createAtsProjectAction = new CreateAtsProjectAction(
            $this->entityManager,
            $this->sendEmailAction
        );
    }

    public function testCreateAtsProjectAction(): void
    {
        $manager = new User();
        $manager->setEmail('some@tomhrm.com');

        $atsProject = new AtsProject();
        $atsProject->setName('test');
        $atsProject->setManager($manager);

        $this->entityManager->expects(self::once())
            ->method('flush');

        $this->sendEmailAction->expects(self::once())
            ->method('execute');

        $this->createAtsProjectAction->execute($atsProject);
    }
}
