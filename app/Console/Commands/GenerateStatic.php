<?php

namespace App\Console\Commands;

use App\Http\Kernel;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class GenerateStatic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-static';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate static website';

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
     * @return int
     */
    public function handle()
    {
        $this->clearBuild();

        system('npm run build');

        $this->recurseCopy('public', 'build', '');
        unlink('build/index.php');

        $this->buildWebpage('/');
        $this->buildWebpage('/assets');
        $this->buildWebpage('/liabilities');
        $this->buildWebpage('/investments');
        $this->buildWebpage('/interest-rates');
        $this->buildWebpage('/charity');
    }

    private function buildWebpage($name)
    {
        $kernel = app(Kernel::class);
        $response = $kernel->handle(Request::create($name));
        $data = $response->getContent();

        $data = str_replace('http://localhost', Env::get('APP_URL'), $data);

        $name = ($name == '/') ? 'index' : $name;
        $index = fopen("build/$name.html", "w") or die("Unable to open file!");
        fwrite($index, $data);
        fclose($index);
    }

    private function clearBuild()
    {
        if (file_exists('build')) {
            system('rm -rf -- build');
        }
        mkdir('build');
    }

    private function recurseCopy(
        string $sourceDirectory,
        string $destinationDirectory,
        string $childFolder = ''
    ): void {
        $directory = opendir($sourceDirectory);

        if (is_dir($destinationDirectory) === false) {
            mkdir($destinationDirectory);
        }

        if ($childFolder !== '') {
            if (is_dir("$destinationDirectory/$childFolder") === false) {
                mkdir("$destinationDirectory/$childFolder");
            }

            while (($file = readdir($directory)) !== false) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                if (is_dir("$sourceDirectory/$file") === true) {
                    $this->recurseCopy("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
                } else {
                    copy("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
                }
            }

            closedir($directory);

            return;
        }

        while (($file = readdir($directory)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (is_dir("$sourceDirectory/$file") === true) {
                $this->recurseCopy("$sourceDirectory/$file", "$destinationDirectory/$file");
            }
            else {
                copy("$sourceDirectory/$file", "$destinationDirectory/$file");
            }
        }

        closedir($directory);
    }
}
