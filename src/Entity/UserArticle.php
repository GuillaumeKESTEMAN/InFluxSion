<?php

namespace App\Entity;

use App\Repository\UserArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;

#[ORM\Entity(repositoryClass: UserArticleRepository::class)]
#[ORM\Table(name: 'users_articles')]
class UserArticle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'boolean', nullable: false)]
    private ?bool $hasViewed = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userArticles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Article::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $article = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getHasViewed(): ?bool
    {
        return $this->hasViewed;
    }

    public function setHasViewed(?bool $hasViewed): void
    {
        $this->hasViewed = $hasViewed;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): void
    {
        $this->article = $article;
    }
}