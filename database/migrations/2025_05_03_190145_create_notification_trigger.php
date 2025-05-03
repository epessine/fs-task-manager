<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
            CREATE OR REPLACE FUNCTION notify_event()
            RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(
                    'events',
                    json_build_object(
                        'table', TG_TABLE_NAME,
                        'action', TG_OP,
                        'data', row_to_json(NEW)
                    )::text
                );
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        SQL);

        DB::unprepared(<<<'SQL'
            CREATE TRIGGER tasks_notify_trigger
            AFTER INSERT OR UPDATE OR DELETE ON tasks
            FOR EACH ROW
            EXECUTE PROCEDURE notify_event();
        SQL);
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS tasks_notify_trigger ON tasks');
        DB::unprepared('DROP FUNCTION IF EXISTS notify_event()');
    }
};
