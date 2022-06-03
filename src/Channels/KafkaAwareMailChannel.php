<?php

declare(strict_types = 1);

namespace Uc\KafkaProducer\Channels;

use Exception;
use Junges\Kafka\Message\Message;
use Uc\KafkaProducer\Events\ProduceMessageEvent;
use Uc\KafkaProducer\Interfaces\CanReceiveNotificationsInterface;
use Uc\KafkaProducer\Interfaces\KafkaMailNotificationInterface;

class KafkaAwareMailChannel extends AbstractKafkaAwareChannel
{
    /**
     * @param \Uc\KafkaProducer\Interfaces\CanReceiveNotificationsInterface $notifiable
     * @param \Uc\KafkaProducer\Interfaces\KafkaMailNotificationInterface   $notification
     *
     * @return void
     */
    public function send(CanReceiveNotificationsInterface $notifiable, KafkaMailNotificationInterface $notification) : void
    {
        $message = $notification->toKafkaMail($notifiable);

        try {
            $messageBody = $message->getValidProperties();
            $messageBody['from'] = env('MAIL_FROM_ADDRESS');

            /** @var Message $kafkaMessage */
            $kafkaMessage = Message::create(config('kafka-producer.mail_topic_name'))
                ->withKey($message->getMessageKey())
                ->withBody($messageBody);

            $this->dispatcher->dispatch(new ProduceMessageEvent($kafkaMessage));
        } catch (Exception $exception) {
            $this->logger->error('Error occurred while sending kafka mail notification!', [
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
