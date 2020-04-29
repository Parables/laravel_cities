<?php

namespace Igaster\LaravelCities\commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use PDO;
use Symfony\Component\Console\Output\ConsoleOutput;
use function storage_path;

class Init extends Command
{
    protected $signature = 'geo:init';

    /** @var PDOConnection */
    protected $pdo;

    public function handle()
    {
        $this->pdo = DB::connection()->getPdo(PDO::FETCH_ASSOC);
        $this->line('Checking geo table exists');
        if (! Schema::hasTable('geo')) {
            $this->line('Running Migrations');
            Artisan::call('migrate', ['--force'], new ConsoleOutput());
        }
        $this->line('Checking for entries in geo table');
        if ($this->tableHasEntries()) {
            $this->line('Geo table is non-empty.....finished!');
            return;
        }
        $this->line('Start download command');
        Artisan::call('geo:download', [], new ConsoleOutput());
        $this->line('Download command finished');
        $this->line('Start seed command');
        Artisan::call('geo:seed', ['--skip-non-empty'], new ConsoleOutput());
        $this->line('Seed command finished');
        $this->line('Cleanup storage');
        $this->cleanupStorage();
        $this->line('All done!!');
        return;
    }

    private function tableHasEntries()
    {
        $sql = "SELECT COUNT(*) as cnt from {$this->getFullyQualifiedTableName()}";
        $stmt = $this->pdo->query($sql);
        if($stmt->execute() === false) {
            $error = "Error in SQL : '$sql'\n" . PDO::errorInfo() ;
            throw new Exception($error, 1);
        }
        return $stmt->fetch(PDO::FETCH_ASSOC)['cnt'] > 0;
    }

    private function cleanupStorage()
    {
       $files =  Storage::allFiles('geo');
       Storage::delete($files);
    }


    /**
     * Get fully qualified table name with prefix if any
     *
     * @return string
     */
    public function getFullyQualifiedTableName() : string
    {
        return DB::getTablePrefix() . 'geo';
    }


}
