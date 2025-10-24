<?php

declare(strict_types=1);

namespace App\Command;

use App\Exceptions\User\UserNotFoundException;
use App\Services\AtsProjectService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:ats_project_add', description: 'Add new ATS project')]
class AtsProjectAddCommand extends Command
{

    public function __construct(
        private readonly AtsProjectService $atsProjectService,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Project name')
            ->addArgument('email', InputArgument::REQUIRED, 'Manager email')
        ;
    }

    /**
     * @throws UserNotFoundException
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int
    {
        $name = $input->getArgument('name');
        $email = $input->getArgument('email');

        $this->atsProjectService->addByNameAndEmail($name, $email);

        return Command::SUCCESS;
    }
}
