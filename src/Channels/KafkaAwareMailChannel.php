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
            /** @var Message $kafkaMessage */
            $kafkaMessage = Message::create(config('kafka-producer.mail_topic_name'))
                ->withKey((string) $message->getProject()->id)
                ->withBody([
                    'recipients' => [$message->getRecipient()],
                    'subject'    => $message->getSubject(),
                    'body'       => $message->getBody(),
                    'from'       => env('MAIL_FROM_ADDRESS'),
                ]);

            $this->dispatcher->dispatch(new ProduceMessageEvent($kafkaMessage));
        } catch (Exception $exception) {
            $this->logger->error('Error occurred while sending kafka mail notification!', [
                'message' => $exception->getMessage(),
            ]);
        }
    }
}