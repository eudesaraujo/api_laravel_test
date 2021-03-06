<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NofaroTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nofaro:test {input} {--requests=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        try {

            $arguments = $this->arguments();
            $input = isset($arguments['input']) ? $arguments['input'] : null;

            $requests = $this->option('requests');

            if(!$this->isInteger($requests) ){
                abort(400,"Informe um número inteiro no parâmetro requests");
            }
         

            $url= env("APP_URL","http://localhost:8000");
            $count = 0;
            $rateLimitPerMinute = 10;
         

            while ($count < $requests) {
                $delay = intval(60/$rateLimitPerMinute);


     
                $response = Http::post("{$url}/api/hash/generate",["input"=>$input]);

                $this->info($response);
     
                $statusCode = $response->status();
     
                $responseArr = $response = json_decode($response,1);

                if(isset($responseArr["error"]) && $responseArr["error"] == "Too Many Attempts."){
                    $this->info("Aguardando 1 minuto...");
                    sleep(60);
                }else{
                    $count++;
                }
                
            
                if($count !== $requests){
                    sleep($delay);
                }

            }
 
            $this->info("Finish");

        } catch(\Exception $Exception){
            $this->error($Exception->getMessage());
        }
     
    }

    function isInteger($input){
        return(ctype_digit(strval($input)));
    }
}
