<?php

declare(strict_types=1);

namespace App\Controller;

use App\Actions\Candidate\CreateCandidateAction;
use App\Actions\Candidate\UpdateCandidateAction;
use App\Entity\AtsProject;
use App\Entity\Candidate;
use App\Exceptions\Candidate\CreateProjectNotActiveException;
use App\Form\CandidateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/candidate')]
final class CandidateController extends AbstractController
{
    #[Route('/details/{id}', name: 'candidate_details', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function details(
        Candidate $candidate
    ): Response {
        return $this->render('candidate/details.html.twig', [
            'candidate' => $candidate,
        ]);
    }

    #[Route('/candidate/new/{id}', name: 'candidate_new', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function new(AtsProject $project): Response {
        $candidate = new Candidate();
        $candidate->setAtsProject($project);
        return $this->render('candidate/form.html.twig', [
            'form' => $this->createNewForm($candidate),
        ]);
    }

    /**
     * @throws CreateProjectNotActiveException
     */
    #[Route('/candidate/create/{id}', name: 'candidate_create', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function create(
        Request $request,
        CreateCandidateAction $createAction,
        AtsProject $project
    ): Response {
        $candidate = new Candidate();
        $candidate->setAtsProject($project);

        $form = $this->createNewForm($candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createAction->execute($candidate);

            return $this->redirectToRoute('candidate_details', ['id' => $candidate->getId()]);
        }

        return $this->render('candidate/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/candidate/{id}/edit', name: 'candidate_edit', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function edit(
        Candidate $candidate
    ): Response {
        return $this->render('candidate/form.html.twig', [
            'form' => $this->createUpdateForm($candidate),
        ]);
    }

    #[Route('/project/{id}/update', name: 'candidate_update', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function update(
        Candidate $candidate,
        Request $request,
        UpdateCandidateAction $updateAction
    ): Response {
        $form = $this->createUpdateForm($candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updateAction->execute($candidate);

            return $this->redirectToRoute('candidate_details', ['id' => $candidate->getId()]);
        }

        return $this->render('candidate/form.html.twig', [
            'form' => $form,
        ]);
    }

    private function createUpdateForm(Candidate $candidate): FormInterface
    {
        $form = $this->createForm(CandidateType::class, $candidate, [
            'action' => $this->generateUrl('candidate_update', ['id' => $candidate->getId()]),
        ]);

        $form->add('submit', SubmitType::class, [
            'label' => 'Update candidate',
        ]);

        return $form;
    }

    private function createNewForm(Candidate $candidate): FormInterface
    {
        $form = $this->createForm(CandidateType::class, $candidate, [
            'action' => $this->generateUrl('candidate_create', ['id' => $candidate->getAtsProject()->getId()]),
        ]);
        $form->add('submit', SubmitType::class, [
            'label' => 'Save candidate',
        ]);

        return $form;
    }
}
