<?php

namespace App\DTO;

class UserSourceDTO
{
    private int $id;
    private string $url;
    private string $name;

    public function __construct(int $id, string $url, string $name)
    {
        $this->id = $id;
        $this->url = $url;
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
