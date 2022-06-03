<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer\Messages;

class KafkaMailMessage
{
    /**
     * @param string $messageKey
     * @param array $recipient
     * @param string $subject
     * @param string $body
     */
    public function __construct(
        protected string $messageKey,
        protected string $recipient,
        protected string $subject,
        protected string $body
    )
    {
    }

    /**
     * @return string
     */
    public function getMessageKey() : string
    {
        return $this->messageKey;
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
}
