<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeederDown extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:seed-down {--class=InitSeeder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revert seeders (run down method of the specified seeder)';

    /**
     * Execute the console command.
     */

    // 

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $seeder = $this->option('class') ?? 'InitSeeder';

        $seederClass = "Database\\Seeders\\{$seeder}";
        if (class_exists($seederClass)) {
            // Cek apakah method 'down' ada pada class tersebut dan apakah bisa dipanggil secara static
            if (is_callable([$seederClass, 'down'])) {
                $this->info("Running static down method for $seederClass...");
                // Panggil method down secara statis
                $seederClass::down();
                $this->info("$seederClass reverted.");
            } else {
                $this->error("The $seederClass does not have a static down method.");
            }
        } else {
            $this->error("Seeder class $seederClass does not exist.");
        }

        return 0;
    }
}
