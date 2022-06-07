<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer\Interfaces;

use Uc\KafkaProducer\Messages\KafkaMailMessage;

interface KafkaMailNotificationInterface
{
    /**
     * Notification resolver for mail channel.
     *
     * @param \Uc\KafkaProducer\Interfaces\CanReceiveNotificationsInterface $notifiable
     *
     * @return \Uc\KafkaProducer\Messages\KafkaMailMessage
     * @see \Uc\KafkaProducer\Channels\KafkaAwareMailChannel
     */
    public function toKafkaMail(CanReceiveNotificationsInterface $notifiable) : KafkaMailMessage;
}
