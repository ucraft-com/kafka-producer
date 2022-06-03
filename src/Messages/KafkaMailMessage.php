<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer\Messages;

use App\Models\Project;

class KafkaMailMessage
{
    /**
     * @param string                   $recipient
     * @param string                   $subject
     * @param string                   $body
     * @param \App\Models\Project|null $project
     */
    public function __construct(
        protected string   $recipient,
        protected string   $subject,
        protected string   $body,
        protected ?Project $project = null
    )
    {
    }

    /**
     * @return string
     */
    public function getRecipient() : string
    {
        return $this->recipient;
    }

    /**
     * @return string
     */
    public function getSubject() : string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getBody() : string
    {
        return $this->body;
    }

    /**
     * @return \App\Models\Project|null
     */
    public function getProject() : ?Project
    {
        return $this->project;
    }
}