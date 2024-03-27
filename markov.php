<?php

include __DIR__.'/vendor/autoload.php';
require_once('./stickers.php');

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;

$discord = new Discord([
    'token' => '<THE_DISCORD_BOT_TOKEN>', // Put your Bot token here from https://discord.com/developers/applications/
    'intents' => Intents::getDefaultIntents() | Intents::MESSAGE_CONTENT // Required to get message content, enable it on https://discord.com/developers/applications/
]);

$discord->on('ready', function (Discord $discord){
    echo 'bot ready' . PHP_EOL;

    // Listen for messages.
    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {
        echo "{$message->author->username}: {$message->content}", PHP_EOL;
        // Note: MESSAGE_CONTENT intent must be enabled to get the content if the bot is not mentioned/DMed.
        if ($message->author->bot){
            echo 'memory usage: ' . memory_get_usage();
            return; //Log will explode over time

        }

        if (str_starts_with($message->content, '!add')){
            if(count(explode(' ', $message->content)) >= 3 && str_starts_with(explode(' ', $message->content)[1], '!')){
                $cmd = explode(' ', $message->content)[1];
                $url = explode(' ', $message->content);
                unset($url[0]);
                unset($url[1]); //Alternative to forcing encoding in php & using strlen to slice at offset
                $url = implode(' ', $url);
                addCMD(openStickerDB(), $cmd, $url); 
                echo('adding CMD: '.$cmd.PHP_EOL);
                echo('adding URL: '.$url.PHP_EOL);
                $message->channel->sendMessage('added '. explode(' ', $message->content)[1]); 
            } else{
                $message->channel->sendMessage('bad command, use !add !commandname texttexttext');
            }
        } elseif(str_starts_with($message->content, '!')){
            $cmd_result = getCMD(openStickerDB(), $message->content);
            if($cmd_result){
                $message->channel->sendMessage($cmd_result);
            }
        }
    });

});

$discord->run();

?>
