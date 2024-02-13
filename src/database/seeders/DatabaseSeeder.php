<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = __DIR__ . '/init_seeder_sqlfiles';
        $files = File::files($path);
        Log::debug('START:' . date('Y-m-d H:i:s') . "\n");
        foreach ($files as $file) {
            Log::debug($file->getpathName() . "\n");
            try {
                DB::unprepared(file_get_contents($file->getpathName()));
            } catch (Exception $e) {
                throw $e;
            }
        }
        Log::debug('END:' . date('Y-m-d H:i:s') . "\n");
    }
}
