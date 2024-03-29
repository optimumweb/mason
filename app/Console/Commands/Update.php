<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mason:update {--branch=origin/master} {--deploy} {--quick}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Mason CMS app.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Updating Mason CMS...");

        $basePath = base_path();
        $branch = $this->option('branch');
        $datetime = date('YmdGis');

        $cmd = implode("; ", [
            "cd {$basePath}",
            "git fetch --all",
            "git branch backup-{$datetime}",
            "git reset --hard {$branch}",
        ]);

        try {
            $this->line(run($cmd));
        } catch (\Exception $e) {
            $this->error($e);
            return Command::FAILURE;
        }

        // Reinstall theme in case composer file was updated
        // Artisan::call('mason:theme:install', [], $this->getOutput());

        if ($this->option('deploy')) {
            Artisan::call(
                command: 'mason:deploy',
                parameters: [
                    '--quick' => $this->option('quick'),
                ],
                outputBuffer: $this->getOutput(),
            );
        }

        $this->info("Mason update completed.");

        return Command::SUCCESS;
    }
}
