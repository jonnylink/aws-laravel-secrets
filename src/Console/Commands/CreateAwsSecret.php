<?php
namespace jonlink\LaravelAwsSecrets\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use jonlink\LaravelAwsSecrets\Support\AwsSecrets;

class RotateAwsSecret extends Command {
    protected $description = 'Sync account roles.';
    protected $signature = 'aws-secret:create {secret_name}
            { --description= : optional description of secret }
            { --kms_id= : optional key management service ID }
    ';

    public function handle(): int {
        $secret_name  = $this->argument('secret_name');
        $secret_value = $this->option('secret_value') ?? bin2hex(random_bytes(36));
        $this->info("Creating secret `{$secret_name}`...");

        try {
            app(AwsSecrets::class)->create(
                secret_name: $secret_name,
                secret: $secret_value,
                description: $description ?? '',
                kms_id: $kms_id ?? null,
            );
        } catch (Exception $exception) {
            $this->error("Unable to rotate secret `{$secret_name}`: {$exception->getMessage()}");
            Log::error($exception);

            return Command::FAILURE;
        }

        $this->info('Done');
        return Command::SUCCESS;
    }
}
