<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer\Tests\Unit;

use DateTimeImmutable;

/**
 * Dummy class for testing purposes.
 */
class Dto
{
    public function __construct(
        protected string            $action,
        protected array             $document,
        protected DateTimeImmutable $dateTime,
    )
    {
    }

    public function getAction() : string
    {
        return $this->action;
    }

    public function getDocument() : array
    {
        return $this->document;
    }

    public function getDateTime() : DateTimeImmutable
    {
        return $this->dateTime;
    }
}
