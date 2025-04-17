<?php

namespace App\UseCase;

use Exception;
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

        $source = $this->sourceRepository->findByUrl($url);
        if ($source && $this->userSourceRepository->findBy(['source' => $source->getId()])) {
            throw new RuntimeException("Cette source existe déjà.");
        } elseif (!$source) {
            $source = new Source();
            $source->setUrl($url);

            $this->sourceRepository->save($source);

            $userSource = new UserSource();
            $userSource->setUser($user);
            $userSource->setSource($source);
            $userSource->setName($name);

            $this->userSourceRepository->save($userSource);
        } else {
            $userSource = new UserSource();
            $userSource->setUser($user);
            $userSource->setSource($source);
            $userSource->setName($name);

            $this->userSourceRepository->save($userSource);
        }
    }

    public function updateSourceName(Source $source, string $newName): void
    {
        $userSource = $this->userSourceRepository->findOneBy(['source' => $source]);
    
        if (!$userSource) {
            throw new \Exception("Impossible de trouver cette source.");
        }
    
        $userSource->setName($newName);
        $this->userSourceRepository->save($userSource);
    }  
    
    public function deleteUserSource(int $userSourceId): void
    {
        $userSource = $this->userSourceRepository->find($userSourceId);

        if (!$userSource) {
            throw new \Exception("Source utilisateur introuvable.");
        }

        $this->userSourceRepository->deleteUserSource($userSource);
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
