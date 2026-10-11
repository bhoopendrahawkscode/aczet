<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\DocumentDataSeeder;

class ImportSheetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aczet:import-sheets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import and synchronize product catalog from Sheet 1, Sheet 2, and Sheet 3';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Aczet Structured Sheets Import...');
        $this->info('Sheet 1: COUNTRY-WISE SHEET (Product Listing & Country Availability)');
        $this->info('Sheet 2: Filter Mapping and Short Product Descriptions');
        $this->info('Sheet 3: Technical Data Sheet (Specifications & Parameters)');

        $seeder = new DocumentDataSeeder();
        $seeder->setCommand($this);
        $seeder->run();

        $this->info('Aczet Structured Sheets Catalog imported successfully!');
        return Command::SUCCESS;
    }
}
