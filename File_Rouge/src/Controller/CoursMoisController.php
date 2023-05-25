<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/cours/mois')]
class CoursMoisController extends AbstractController
{
    #[Route('/mois', name: 'app_cours_mois_index', methods: ['GET'])]
    public function index(
        CoursRepository $coursRepository,
        Request $request,
        PaginatorInterface $paginatorInterface
        ): Response
    {

        
            // Paginer les cours
            $cours = $paginatorInterface->paginate(
                $coursRepository->findAll(), // Les données à paginer
                $request->query->getInt('page', 1), // Le numéro de la page courante
                5 // Le nombre d'éléments à afficher par page
            );

        return $this->render('cours_mois/index.html.twig', [
            'cours' => $cours,
        ]);
    }

    #[Route('/new', name: 'app_cours_mois_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CoursRepository $coursRepository): Response
    {
        $cour = new Cours();
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coursRepository->save($cour, true);

            $this->addFlash('success', 'Votre action a bien etait réaliser');

            return $this->redirectToRoute('app_cours_mois_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cours_mois/new.html.twig', [
            'cour' => $cour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cours_mois_show', methods: ['GET'])]
    public function show(Cours $cour): Response
    {
        return $this->render('cours_mois/show.html.twig', [
            'cour' => $cour,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cours_mois_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cours $cour, CoursRepository $coursRepository): Response
    {
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coursRepository->save($cour, true);

            return $this->redirectToRoute('app_cours_mois_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cours_mois/edit.html.twig', [
            'cour' => $cour,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_cours_mois_delete', methods: ['POST'])]
    public function delete(Request $request, Cours $cour, CoursRepository $coursRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cour->getId(), $request->request->get('_token'))) {
            $coursRepository->remove($cour, true);
        }

        return $this->redirectToRoute('app_cours_mois_index', [], Response::HTTP_SEE_OTHER);
    }
}
