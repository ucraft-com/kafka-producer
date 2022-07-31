<?php

namespace Uc\KafkaProducer\Tests\Unit;

use DateTimeImmutable;
use Symfony\Component\Serializer\Context\Normalizer\DateTimeNormalizerContextBuilder;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\UidNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Uc\KafkaProducer\Tests\TestCase;

class SerializerTest extends TestCase
{
    public function testSerialize_WithComplexObject_KeepsSpecifiedDateFormat() : void
    {
        $dto = new Dto(
            action: 'Something good happened!',
            document: [
                'foo'  => 'bar',
                'date' => new DateTimeImmutable('2022-02-02 12:22:22')
            ],
            dateTime: new DateTimeImmutable('2022-03-03 13:33:33')
        );

        $serialized = $this->serialize($dto);

        $this->assertEquals(
            '{"action":"Something good happened!","document":{"foo":"bar","date":"2022-02-02 12:22:22"},"dateTime":"2022-03-03 13:33:33"}',
            $serialized
        );
    }

    public function testSerialize_WithNullValues_SkipsNullValues() : void
    {
        $dto = (object) ['firstName' => 'John', 'lastName' => null];

        $serialized = $this->serialize($dto);

        $this->assertEquals('{"firstName":"John"}', $serialized);
    }

    public function testSerialize_WithUninitializedValues_SkipsUninitializedValues() : void
    {
        $dto = new class ('foo', 20) {
            public string $bar;

            public function __construct(
                public string $foo,
                public int    $age,
            )
            {
            }
        };

        $serialized = $this->serialize($dto);

        $this->assertEquals('{"foo":"foo","age":20}', $serialized);
    }

    /**
     * Serialize given data.
     * The same context builders must be used during Kafka message serialization.
     *
     * @param mixed $data
     *
     * @return string
     */
    protected function serialize(mixed $data) : string
    {
        $serializer = $this->createSerializer();

        $initialContextBuilder = (new DateTimeNormalizerContextBuilder())
            ->withFormat('Y-m-d H:i:s');

        $contextBuilder = (new ObjectNormalizerContextBuilder())
            ->withContext($initialContextBuilder)
            ->withSkipNullValues(true)
            ->withSkipUninitializedValues(true)
            ->withPreserveEmptyObjects(true);

        return $serializer->serialize($data, 'json', $contextBuilder->toArray());
    }

    /**
     * Create instance of the Serializer.
     * The same serialization configurations must be used during Kafka message serialization.
     *
     * @return \Symfony\Component\Serializer\SerializerInterface
     */
    protected function createSerializer() : SerializerInterface
    {
        $normalizers = [
            new JsonSerializableNormalizer(), new UidNormalizer(),
            new DateTimeNormalizer(), new ObjectNormalizer()
        ];

        $encoders = [new JsonEncoder()];

        return new Serializer($normalizers, $encoders);
    }
}
