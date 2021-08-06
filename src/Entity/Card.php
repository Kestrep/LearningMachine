<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CardRepository::class)
 */
class Card
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("card:read")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text")
     * @Groups("card:read")
     */
    private $frontMainContent;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("card:read")
     */
    private $frontSubcontent;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("card:read")
     */
    private $backMainContent;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $backSubcontent;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $frontClue;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $backClue;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="integer")
     * @Groups("card:read")
     */
    private $stage;

    /**
     * @ORM\ManyToOne(targetEntity=Subcategory::class, inversedBy="cards")
     */
    private $subcategory;

    /**
     * @ORM\Column(type="datetime")
     */
    private $playAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getFrontMainContent(): ?string
    {
        return $this->frontMainContent;
    }

    public function setFrontMainContent(?string $frontMainContent): self
    {
        $this->frontMainContent = $frontMainContent;

        return $this;
    }

    public function getFrontSubcontent(): ?string
    {
        return $this->frontSubcontent;
    }

    public function setFrontSubcontent(?string $frontSubcontent): self
    {
        $this->frontSubcontent = $frontSubcontent;

        return $this;
    }

    public function getBackMainContent(): ?string
    {
        return $this->backMainContent;
    }

    public function setBackMainContent(?string $backMainContent): self
    {
        $this->backMainContent = $backMainContent;

        return $this;
    }

    public function getBackSubcontent(): ?string
    {
        return $this->backSubcontent;
    }

    public function setBackSubcontent(?string $backSubcontent): self
    {
        $this->backSubcontent = $backSubcontent;

        return $this;
    }

    public function getFrontClue(): ?string
    {
        return $this->frontClue;
    }

    public function setFrontClue(?string $frontClue): self
    {
        $this->frontClue = $frontClue;

        return $this;
    }

    public function getBackClue(): ?string
    {
        return $this->backClue;
    }

    public function setBackClue(?string $backClue): self
    {
        $this->backClue = $backClue;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getStage(): ?int
    {
        return $this->stage;
    }

    public function setStage(int $stage): self
    {
        $this->stage = $stage;

        return $this;
    }

    public function getSubcategory(): ?Subcategory
    {
        return $this->subcategory;
    }

    public function setSubcategory(?Subcategory $subcategory): self
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    public function getPlayAt(): ?\DateTimeInterface
    {
        return $this->playAt;
    }

    public function setPlayAt(\DateTimeInterface $playAt): self
    {
        $this->playAt = $playAt;

        return $this;
    }
}
