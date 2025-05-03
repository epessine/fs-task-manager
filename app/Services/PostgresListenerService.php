<?php

namespace App\Services;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;

class PostgresListenerService
{
    public function listen()
    {
        DB::connection()->getPdo()->exec('LISTEN events');

        while (true) {
            if ($notification = DB::connection()->getPdo()->pgsqlGetNotify(\PDO::FETCH_ASSOC)) {
                $data = json_decode($notification['payload'], true);
                Broadcast::on('updates')->as('data.updated')->with($data)->send();
                logger()->info('PostgreSQL notification received', $data);
            }
            usleep(100000);
        }
    }
}
