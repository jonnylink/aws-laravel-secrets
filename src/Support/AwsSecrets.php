<?php
namespace jonnylink\AwsLaravelSecrets\Support;

use Aws\SecretsManager\SecretsManagerClient;
use stdClass;

class AwsSecrets {
    private SecretsManagerClient $client;

    public function __construct() {
        $params = [
            'version' => 'latest',
            'region'  => 'us-east-1',
        ];

        $this->client = new SecretsManagerClient($params);
    }

    // returns the ARN of the new secret
    public function create(string $secret_name, string|array $secret, string $description = '', array $tags = [], string $kms_id = ''): string {
        $result = $this->client->createSecret([
            'Description'  => $description ?: "Secret for {$secret_name} created by Laravel",
            'Name'         => $secret_name,
            // strings are allowed, but discouraged follow AWS guidance and use JSON
            'SecretString' => json_encode($secret),
            'KmsKeyId'     => $kms_id ?: config('aws.secrets.kms_id'),
            'Tags'         => $tags ?: [
                [
                    'Key' => 'Name',
                    'Value' => $secret_name,
                ],
                [
                    'Key' => 'Stage',
                    'Value' => app()->environment(),
                ],
            ],
        ]);

        return $result['ARN'];
    }

    public function update(string $secret_id, string $secret): void {
        $this->client->updateSecret([
            'SecretId'     => $secret_id,
            'SecretString' => $secret,
        ]);
    }

    // returns an object if the secret is key/value, otherwise a string
    public function get(string $secret_id): null|string|stdClass {
        $result = $this->client->getSecretValue(['SecretId' => $secret_id]);

        if (isset($result['SecretString'])) {
            $secret_string = $result['SecretString'];

            // can handle a string or an object
            return json_decode($secret_string) ?: $secret_string;
        }

        return null;
    }

    public function delete(string $secret_id): void {
        $this->client->deleteSecret(['SecretId' => $secret_id]);
    }

    public function list(null|string|array $filters = null): array {
        if ($filters) {
            $params = ['Filters' => [[ 'Key' => 'all', 'Values' => (array) $filters]]];
        }

        $result = $this->client->listSecrets($params ?? []);

        return $result['SecretList'];
    }

    public function describe(string $secret_id): array {
        $result = $this->client->describeSecret(['SecretId' => $secret_id]);

        return $result->toArray();
    }
}
