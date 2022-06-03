<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer\Messages;

use Exception;

class KafkaMailMessage
{
    /**
     * @param string      $messageKey
     * @param array       $recipients
     * @param string|null $subject
     * @param string|null $template
     * @param string|null $payload
     * @param string|null $body
     */
    public function __construct(
        protected string  $messageKey,
        protected array   $recipients,
        protected ?string $subject = null,
        protected ?string $template = null,
        protected ?string $payload = null,
        protected ?string $body = null
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
     * @return array
     */
    public function getRecipients() : array
    {
        return $this->recipients;
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

    public function getValidProperties() : array
    {
        $data = [
            'recipients' => $this->recipients,
            'subject'    => $this->subject,
        ];

        if ($this->template && $this->payload) {
            $data['template'] = $this->template;
            $data['payload'] = $this->payload;
        } elseif ($this->body) {
            $data['body'] = $this->body;
        } else {
            throw new Exception('Message data is invalid. Expected template with payload, or body.');
        }

        return $data;
    }
}
