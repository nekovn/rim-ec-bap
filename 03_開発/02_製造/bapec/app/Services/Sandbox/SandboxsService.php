<?php
namespace App\Services\Sandbox;

use App\Services\SimpleCrudServiceTrait;

class SandboxsService
{
    use SimpleCrudServiceTrait;

    public function __construct()
    {
    }

    public function getScreenSelections(): array
    {
        return [];
    }
}
