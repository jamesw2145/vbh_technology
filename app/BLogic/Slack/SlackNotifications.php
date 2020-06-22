<?php

namespace App\BLogic\Slack;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

use App\Vendor\Slack\Slack;
use App\Vendor\Slack\SlackMessage;

class SlackNotifications extends Command
{
    public function notifyDailyTrading($message) {
        $slack = new Slack('https://hooks.slack.com/services/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');
        $sm = new SlackMessage($slack);
        $sm->setText($message);
        $sm->send();

    }

    public function notifyPositionTrading($message) {
        $slack = new Slack('https://hooks.slack.com/services/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');
        $sm = new SlackMessage($slack);
        $sm->setText($message);
        $sm->send();
    }

    public function notifyHitbtcDailyTradingDrop($message) {
        $slack = new Slack('https://hooks.slack.com/services/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');
        $sm = new SlackMessage($slack);
        $sm->setText($message);
        $sm->send();
    }

    public function notifyHitbtcDailyTradingIncrease($message) {
        $slack = new Slack('https://hooks.slack.com/services/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');
        $sm = new SlackMessage($slack);
        $sm->setText($message);
        $sm->send();
    }
}
