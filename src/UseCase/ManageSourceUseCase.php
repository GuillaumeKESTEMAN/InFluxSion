<?php

namespace App\UseCase;

use RuntimeException;
use App\Entity\Source;
use App\Entity\UserSource;
use App\Repository\SourceRepository;
use App\Repository\UserSourceRepository;

class ManageSourceUseCase
{
    public function __construct(private SourceRepository $sourceRepository, private UserSourceRepository $userSourceRepository) {}

    public function addSource(string $url, string $name, $user): void
    {
        if (!$this->isValidRSS($url)) {
            throw new RuntimeException("L'URL fournie n'est pas un flux RSS valide.");
        }

        if ($this->sourceRepository->findByUrl($url)) {
            throw new RuntimeException("Cette source existe déjà.");
        }

        $source = new Source();
        $source->setUrl($url);
        $this->sourceRepository->save($source);

        $userSource = new UserSource();
        $userSource->setUser($user);
        $userSource->setSource($source);
        $userSource->setName($name);

        $this->userSourceRepository->save($userSource);
    }

    private function isValidRSS(string $url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $rss = @simplexml_load_file($url);
        return $rss && isset($rss->channel);
    }
}
