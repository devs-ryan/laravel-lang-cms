<?php

namespace DevsRyan\LaravelLangCMS\Commands;

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
        //check valid target
        $target = $this->argument('target');
        if ($target != 'support_email' && $target != 'password') {
            $this->info("Target argument must be `support_email` or `password`");
            return;
        }
        
        //check public env exists
        $path = public_path('lang-cms/env.php');
        if (!file_exists($path)) {
            $this->info("Unable to open settings file, run `php artisan vendor:publish --tag=public --force`");
            return;
        }
        $handle = fopen($path, "r") or die("Unable to read file!");
        $overwrite_string = "";
        
        if ($target == 'support_email') {
            $key = 'SUPPORT_EMAIL';
        }
        else {
            $key = 'ACCESS_PASSWORD';
        }
        
        $input = $this->ask("Enter new value for " . strtolower($key));
        
        //check illegal characters
        if ($this->illegalChars($input)) {
            $this->info("Illegal characters detected in input. Allowed Characters: [A-z0-9_.?@!$-]");
            return;
        }
        
        //check email valid 
        if ($target == 'support_email') {
            if (!$this->checkEmail($input)) {
                $this->info("Please enter a valid email address.");
                return;
            }
        }
        
         //parse for correct line
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (strpos($line, $key) === false) {
                    $overwrite_string .= $line;
                }
                else {
                    $sep = explode(" => ", $line);
                    $replace = explode(",", $sep[count($sep) - 1])[0];
                    $overwrite_string .= "    '$key' => '" . $input . "',\n";
                }
            }
            fclose($handle);
        }
        //replace line with new value
        file_put_contents($path, $overwrite_string) or die("Unable to write to file!");
        $this->info("Lang CMS updated successfully.");
    }
    
    //check valid email
    private function checkEmail($email) {
       $find1 = strpos($email, '@');
       $find2 = strpos($email, '.');
       return ($find1 !== false && $find2 !== false && $find2 > $find1);
    }
    
    //check illegal characters
    private function illegalChars($input) {
        return !preg_match('/^([a-zA-Z0-9\._$\-\@\!\?\$]+)$/', $input);
    }
    
}















