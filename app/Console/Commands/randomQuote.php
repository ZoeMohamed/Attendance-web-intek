<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\NotificationMod;
class randomQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'random:quote';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Random Quote';

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
        //
        $dataquote = $this->randomQuote();

        $itung = count($dataquote);
        $rand = rand(0, $itung);

        //dd($dataquote[$rand]);
        NotificationMod::where('id', 3)->update(["contents"=> $dataquote[$rand]->text."\nQuoteBy: ".$dataquote[$rand]->author.""]);
        
    }
    private function randomQuote()
    {
        $get = file_get_contents("https://type.fit/api/quotes");
        $dec = json_decode($get);
        return $dec;
    }
}
