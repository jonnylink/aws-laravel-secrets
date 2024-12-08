<?php
namespace jonnylink\AwsLaravelSecrets\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use jonnylink\AwsLaravelSecrets\Support\AwsSecrets;

class RotateAwsSecret extends Command {
    protected $description = 'Sync account roles.';
    protected $signature = 'aws-secret:get {secret_name}';

    public function handle(): int {
        $secret_id = $this->argument('secret_name');

        try {
            $this->info(app(AwsSecrets::class)->get(secret_id: $secret_id));
        } catch (Exception $exception) {
            $this->error("Unable to get secret `{$secret_id}`: {$exception->getMessage()}");
            Log::error($exception);

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
