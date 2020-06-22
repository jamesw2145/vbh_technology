<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

use App\Vendor\Slack\Slack;
use App\Vendor\Slack\SlackMessage;

class TestSlack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:Slack';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Slack';

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

        // Use the url you got earlier
        $slack = new Slack('https://hooks.slack.com/services/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');  // Daily trading notifications
                            // https://hooks.slack.com/services/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  // position trading.
        // Create a new message
        $message = new SlackMessage($slack);
        $message->setText("XRP Up by 20%");
        $message->send();
    }
}
