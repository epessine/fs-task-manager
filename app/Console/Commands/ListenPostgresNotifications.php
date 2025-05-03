<?php

namespace App\Console\Commands;

use App\Services\PostgresListenerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Concurrency;

class ListenPostgresNotifications extends Command
{
    protected $signature = 'postgres:listen';

    protected $description = 'Listen for PostgreSQL notifications';

    public function handle()
    {
        $this->info('Starting PostgreSQL listener...');

        Concurrency::driver('fork')->run([
            fn () => Artisan::call('queue:work'),
            fn () => Artisan::call('reverb:start'),
            fn () => (new PostgresListenerService)->listen(),
        ]);
    }
}
