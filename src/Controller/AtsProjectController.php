<?php

declare(strict_types=1);

namespace App\Controller;

use App\Actions\AtsProject\CreateAtsProjectAction;
use App\Actions\AtsProject\DeleteAtsProjectAction;
use App\Actions\AtsProject\UpdateAtsProjectAction;
use App\Entity\AtsProject;
use App\Form\AtsProjectType;
use App\Repository\AtsProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ats')]
final class AtsProjectController extends AbstractController
{
    #[Route('/projects', name: 'ats_projects', methods: ['GET'])]
    public function index(
        AtsProjectRepository $repository
    ): Response {
        return $this->render('ats/project/index.html.twig', [
            'projects' => $repository->findAll(),
        ]);
    }

    #[Route('/project/{id}', name: 'ats_project', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function details(
        AtsProject $project
    ): Response {
        return $this->render('ats/project/details.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/project/new', name: 'ats_project_new', methods: ['GET'])]
    public function new(): Response {
        return $this->render('ats/project/form.html.twig', [
            'form' => $this->createNewForm(new AtsProject()),
        ]);
    }

    #[Route('/project/create', name: 'ats_project_create', methods: ['POST'])]
    public function create(
        Request $request,
        CreateAtsProjectAction $createAction
    ): Response {
        $project = new AtsProject();

        $form = $this->createNewForm($project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createAction->execute($project);

            return $this->redirectToRoute('ats_project', ['id' => $project->getId()]);
        }

        return $this->render('ats/project/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/project/{id}/edit', name: 'ats_project_edit', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function edit(
        AtsProject $project
    ): Response {
        return $this->render('ats/project/form.html.twig', [
            'form' => $this->createUpdateForm($project),
        ]);
    }

    #[Route('/project/{id}/update', name: 'ats_project_update', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function update(
        AtsProject $project,
        Request $request,
        UpdateAtsProjectAction $updateAction
    ): Response {
        $form = $this->createUpdateForm($project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updateAction->execute($project);

            return $this->redirectToRoute('ats_project', ['id' => $project->getId()]);
        }

        return $this->render('ats/project/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/project/delete/{id}', name: 'ats_project_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(
        AtsProject $project,
        DeleteAtsProjectAction $deleteAtsProjectAction
    ): Response {
        $deleteAtsProjectAction->execute($project);
        return $this->redirectToRoute('ats_projects');
    }

    private function createUpdateForm(AtsProject $project): FormInterface
    {
        $form = $this->createForm(AtsProjectType::class, $project, [
            'action' => $this->generateUrl('ats_project_update', ['id' => $project->getId()]),
        ]);

        $form->add('submit', SubmitType::class, [
            'label' => 'Update project',
        ]);

        return $form;
    }

    private function createNewForm(AtsProject $project): FormInterface
    {
        $form = $this->createForm(AtsProjectType::class, $project, [
            'action' => $this->generateUrl('ats_project_create'),
        ]);
        $form->add('submit', SubmitType::class, [
            'label' => 'Save project',
        ]);

        return $form;
    }
}
