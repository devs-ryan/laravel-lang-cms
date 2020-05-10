<?php

namespace Raysirsharp\LaravelLangCMS\Commands;

use Illuminate\Console\Command;

class SetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lang-cms:set {target}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the support email, or password';

    /**
     * Create a new command instance.
     *
     * @return void
     */
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
        
    }
    
}