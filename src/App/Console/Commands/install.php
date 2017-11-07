<?php

namespace z5internet\RufOAuth\App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Filesystem\Filesystem;

use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\MountManager;

class install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rufOAuth:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install RufOAuth resources';

    public function __construct()
    {

        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $scripts = collect([
            \z5internet\RufOAuth\installationFiles\scripts\config::class,
            \z5internet\RufOAuth\installationFiles\scripts\migrations::class,
        ]);

        $scripts->each(function ($installer) { (new $installer($this))->install(__DIR__); });

    }

}
