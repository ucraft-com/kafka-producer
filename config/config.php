<?php

return [
    /*
     | Client identifier.
     */
    'client_id'                             => env('KAFKA_CLIENT_ID', 'php-kafka-producer'),

    /*
     | When set to true, the producer will ensure that messages are successfully produced exactly once and in the original produce order.
     | The following configuration properties are adjusted automatically (if not modified by the user)
     | when idempotence is enabled: max.in.flight.requests.per.connection=5 (must be less than or equal to 5),
     | retries=INT32_MAX (must be greater than 0), acks=all, queuing.strategy=fifo.
     | Producer instantiation will fail if user-supplied configuration is incompatible.
     */
    'idempotence'                           => env('KAFKA_ENABLE_IDEMPOTENCE', 'true'),

    /*
     | Endpoint identification algorithm to validate broker hostname using broker certificate.
     | https - Server (broker) hostname verification as specified in RFC2818. none - No endpoint verification.
     | OpenSSL >= 1.0.2 required.
     */
    'ssl_endpoint_identification_algorithm' => env('KAFKA_SSL_ENDPOINT_IDENTIFICATION_ALGORITHM', 'https'),

    /*
     | Protocol used to communicate with brokers. Values are plaintext, ssl, sasl_plaintext, sasl_ssl.
     */
    'security_protocol'                     => env('KAFKA_SECURITY_PROTOCOL', 'SASL_SSL'),

    /*
     | SASL mechanism to use for authentication. Supported: GSSAPI, PLAIN, SCRAM-SHA-256, SCRAM-SHA-512, OAUTHBEARER.
     | NOTE: Despite the name only one mechanism must be configured.
     */
    'sasl_mechanisms'                       => env('KAFKA_SASL_MECHANISM', 'PLAIN'),

    /*
     | SASL username for use with the PLAIN and SASL-SCRAM-.. mechanisms.
     */
    'sasl_username'                         => env('KAFKA_SASL_USERNAME', ''),

    /*
     | SASL password for use with the PLAIN and SASL-SCRAM-.. mechanism
     */
    'sasl_password'                         => env('KAFKA_SASL_PASSWORD', ''),

    /*
     | Default timeout for network requests.
     | Producer: ProduceRequests will use the lesser value of socket.timeout.ms and remaining message.timeout.ms for the first message in the batch.
     | Consumer: FetchRequests will use fetch.wait.max.ms + socket.timeout.ms.
     | Admin: Admin requests will use socket.timeout.ms or explicitly set rd_kafka_AdminOptions_set_operation_timeout() value.
     */
    'socket_timeout_ms'                     => env('KAFKA_SOCKET_TIMEOUT_MS', '50'),
];
