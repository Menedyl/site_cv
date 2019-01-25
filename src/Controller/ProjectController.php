<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Service\ProjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{

    /**
     * @Route("/projects/{slug}-{id}", name="project_show", requirements={"slug": "[a-z0-9\-]*"})
     * @Cache(expires="tomorrow", public=true, maxage="21600", smaxage="21600", vary={"User-Agent", "Cookie", "Accept-Encoding"})
     *
     * @param Project $project
     */
    public function show(Project $project, string $slug): Response
    {

        if ($project->getSlug() !== $slug) {
            return $this->redirectToRoute('project_show', [
                'slug' => $project->getSlug(),
                'id' => $project->getId()
            ], 301);
        }

        return $this->render('project/show.html.twig', ['project' => $project]);
    }


    /**
     * @Route("/projects/add", name="project_add")
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Request $request
     * @param ProjectManager $manager
     */
    public function add(Request $request, ProjectManager $manager): Response
    {
        /**
         * @var Project $project
         */
        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
            $manager->add($project);

            $this->addFlash('success', 'Projet ajouté !');

            return $this->redirectToRoute('project_list');
        }
        return $this->render('project/add.html.twig', [
            'form' => $form->createView(),
            'current_page' => 'project_add'
        ]);
    }


    /**
     * @Route("/projects/{id}/edit", name="project_edit")
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Request $request
     * @param Project $project
     * @param ProjectManager $manager
     */
    public function edit(Request $request, Project $project, ProjectManager $manager): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
            $manager->edit($project);

            $this->addFlash('success', 'Projet modifié !');


            return $this->redirectToRoute('project_list');
        }
        return $this->render('project/edit.html.twig', [
            'pictures' => $project->getPictures(),
            'form' => $form->createView()]);
    }


    /**
     * @Route("/projects/{id}/delete", name="project_delete")
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Project $project
     * @param ProjectManager $manager
     */
    public function delete(Request $request, Project $project, ProjectManager $manager): Response
    {
        if ($this->isCsrfTokenValid('delete_project', $request->get('_token'))) {
            $manager->delete($project);
        }

        $this->addFlash('success', 'Projet supprimé !');


        return $this->redirectToRoute('project_list');
    }


    /**
     * @Route("/projects", name="project_list")
     * @Cache(expires="tomorrow", public=true, maxage="21600", smaxage="21600", vary={"User-Agent", "Cookie", "Accept-Encoding"})
     */
    public function list(EntityManagerInterface $em): Response
    {
        $projects = $em->getRepository(Project::class)->findAllDesc();

        return $this->render('project/list.html.twig', [
            'projects' => $projects,
            'current_page' => 'project_list'
        ]);
    }
}
