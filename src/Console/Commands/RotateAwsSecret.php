<?php
namespace jonlink\LaravelAwsSecrets\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use jonlink\LaravelAwsSecrets\Support\AwsSecrets;

class RotateAwsSecret extends Command {
    protected $description = 'Sync account roles.';
    protected $signature = 'aws-secret:rotate {secret_name}
            { --secret_value= : optional secret value instead of auto-generating one }
    ';

    public function handle(): int {
        $secret_id = $this->argument('secret_name');
        $new_value = $this->option('secret_value') ?? bin2hex(random_bytes(36));
        $this->info("Rotating secret `{$secret_id}`...");

        try {
            app(AwsSecrets::class)->update(secret_id: $secret_id, secret: $new_value);
        } catch (Exception $exception) {
            $this->error("Unable to rotate secret `{$secret_id}`: {$exception->getMessage()}");
            Log::error($exception);

            return Command::FAILURE;
        }

        $this->info('Done');
        return Command::SUCCESS;
    }
}
