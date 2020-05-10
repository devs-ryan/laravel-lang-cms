<?php

namespace Raysirsharp\LaravelLangCMS\Commands;

use Illuminate\Console\Command;

class EnableEditCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lang-cms:edit {--off} {--on}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Toggle the Language CMS edit ability on/off';

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
        //check public env exists
        $path = public_path('lang-cms/env.php');
        if (!file_exists($path)) {
            $this->info("Unable to open settings file, run `php artisan vendor:publish --tag=public --force`");
            return;
        }
        $handle = fopen($path, "r") or die("Unable to read file!");
        
        $overwrite_string = "";
        
        //get input
        if ($this->option('off')) $action = 'false';
        else $action = 'true';
        
        //parse for correct line
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (strpos($line, 'EDIT_ONLY') === false) {
                    $overwrite_string .= $line;
                }
                else {
                    $sep = explode(" => ", $line);
                    $replace = explode(",", $sep[count($sep) - 1])[0];
                    $overwrite_string .= "    'EDIT_ONLY' => " . $action . ",\n";
                }
            }
            fclose($handle);
        }
        //replace line with new value
        file_put_contents($path, $overwrite_string) or die("Unable to write to file!");
        $this->info("Lang CMS updated successfully.");
    }
    
}