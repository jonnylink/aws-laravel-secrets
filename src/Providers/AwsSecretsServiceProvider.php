<?php
namespace jonnylink\AwsLaravelSecrets\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use jonnylink\AwsLaravelSecrets\Support\AwsSecrets;

class AwsSecretsServiceProvider extends ServiceProvider implements DeferrableProvider {
    public function register(): void {
        $this->app->singleton(AwsSecrets::class, fn(): AwsSecrets => new AwsSecrets());
    }

    public function provides(): array {
        return [AwsSecrets::class];
    }
}
