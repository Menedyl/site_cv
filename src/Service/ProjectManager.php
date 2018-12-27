<?php

namespace App\Service;

use App\Entity\Picture;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;

class ProjectManager
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * ProjectManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Add a project in the DataBase and update the pictures attributes
     *
     * @param Project $project
     */
    public function add(Project $project)
    {
        $this->em->persist($project);
        $this->em->flush();
    }

    /**
     * Update a project in the DB with the pictures
     *
     * @param Project $project
     * @param string $paramDirectory
     */
    public function edit(Project $project)
    {
        $oldPictures = $this->em->getRepository(Picture::class)->findBy(['project' => $project->getId()]);

        foreach ($oldPictures as $picture) {
            if (!$project->getPictures()->contains($picture)) {
                $this->em->remove($picture);
            }
        }
        $this->em->flush();
    }

    /**
     * Delete a project in the DB with the pictures
     *
     * @param Project $project
     * @param string $paramDirectory
     */
    public function delete(Project $project)
    {
        $this->em->remove($project);
        $this->em->flush();
    }
}
