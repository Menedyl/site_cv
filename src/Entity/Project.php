<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="Ce champ doit être rempli."
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(
     *     message="Ce champ doit être rempli."
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Url(
     *     message="L'URL {{ value }} n'est pas une URL valide."
     * )
     */
    private $link;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Picture", mappedBy="project", cascade={"all"})
     * @Assert\NotBlank()
     */
    private $pictures;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;


   
    public function __construct()
    {
        $this->createAt = new \DateTime();
        $this->pictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): string
    {
        return (new Slugify())->slugify($this->getName());
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;

    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;

    }

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): void
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
            $picture->setProject($this);
        }
    }

    public function removePicture(Picture $picture): void
    {
        if ($this->pictures->contains($picture)) {
            $this->pictures->removeElement($picture);
            // set the owning side to null (unless already changed)
            if ($picture->getProject() === $this) {
                $picture->setProject(null);
            }
        }
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }
}
