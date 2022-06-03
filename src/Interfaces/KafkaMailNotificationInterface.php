<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer\Interfaces;

use Uc\KafkaProducer\Messages\KafkaMailMessage;

interface KafkaMailNotificationInterface
{
    /**
     * @param \Uc\KafkaProducer\Interfaces\CanReceiveNotificationsInterface $notifiable
     *
     * @return \Uc\KafkaProducer\Messages\KafkaMailMessage
     */
    public function toKafkaMail(CanReceiveNotificationsInterface $notifiable) : KafkaMailMessage;
}