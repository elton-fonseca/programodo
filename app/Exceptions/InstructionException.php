<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class InstructionException extends Exception
{
    public function __construct(
        string $message,
        private string $title
    ) {
        parent::__construct($message);
    }

    /**
     * Get the value of title
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
