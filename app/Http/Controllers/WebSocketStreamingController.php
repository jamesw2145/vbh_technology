<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
// use React\EventLoop\Factory;

// Before using make sure to set the webSocketStreaming. See TimeIntervalStreaming as an example.
class WebSocketStreamingController extends Controller implements MessageComponentInterface {

    // Set this to the appropriate streaming controller.
    public $webSocketStreaming;

    private $connections = [];

    function __construct() {
    }

    public function initializeLoop(\React\EventLoop\LoopInterface $loop) {
        $loop->addPeriodicTimer(30, function () {
            $this->sendStreamingData();
        });         
    }

    function sendStreamingData () {
        $streamingData = $this->webSocketStreaming->retrieveStreamingData();
        if ($streamingData !== FALSE) {
            $json = json_encode($streamingData);

            Log::info("WebSocket: sendStreamingData -- json data: [" . print_r($json, true) . "]");

            foreach($this->connections as $resourceId => &$connection){
                $connection['conn']->send($json);
            }
        }
    }

     /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    function onOpen(ConnectionInterface $conn) {
        Log::info("WebSocket: onOpen -- resourceId: " . $conn->resourceId);
        $this->connections[$conn->resourceId] = compact('conn') + ['user_id' => null];
    }
    
     /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    function onClose(ConnectionInterface $conn){
        Log::info("WebSocket: onClose -- resourceId: " . $conn->resourceId);
        $disconnectedId = $conn->resourceId;
        unset($this->connections[$disconnectedId]);
        foreach($this->connections as &$connection)
            $connection['conn']->send(json_encode([
                'offline_user' => $disconnectedId,
                'from_user_id' => 'server control',
                'from_resource_id' => null
            ]));
    }
    
     /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    function onError(ConnectionInterface $conn, \Exception $e){        
        $userId = $this->connections[$conn->resourceId]['user_id'];
        Log::info ("WebSocket: onError -- resourceId: " . $conn->resourceId . " userId: " . $userId . " error: " . $e->getMessage());
        unset($this->connections[$conn->resourceId]);
        $conn->close();
    }
    
     /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $conn The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    function onMessage(ConnectionInterface $conn, $msg){

    }
    
}