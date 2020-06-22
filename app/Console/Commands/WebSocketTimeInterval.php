<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\Http\Controllers\WebSocketStreamingController;
use App\Api\TimeIntervalStreaming;

class WebSocketTimeInterval extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'websocket:timeinterval';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initializes the WebSocket server to handle TimeInterval.';

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
    public function handle() {
        date_default_timezone_set('America/New_York');        

        $streamingController = new WebSocketStreamingController();
        $streamingController->webSocketStreaming = new TimeIntervalStreaming();

        $loop = \React\EventLoop\Factory::create();
        $socket = new \React\Socket\Server('0.0.0.0:8090', $loop);
        $server = new IoServer(new HttpServer(new WsServer($streamingController)), $socket, $loop);
        $streamingController->initializeLoop($loop);
        $server->run();
        return;
    }
}
