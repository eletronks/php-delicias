<?php
require __DIR__ . '/vendor/autoload.php';

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;

// ------------------------------------------------------------
// Essência Vital
// ------------------------------------------------------------
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// ------------------------------------------------------------
// Despertar do Caçador
// ------------------------------------------------------------
$discord = new Discord([
    'token'   => $_ENV['DISCORD_TOKEN'],
    'intents' => \Discord\WebSockets\Intents::getDefaultIntents() 
                 | \Discord\WebSockets\Intents::MESSAGE_CONTENT
]);

// ------------------------------------------------------------
// O Núcleo da Armadura
// ------------------------------------------------------------
$discord->on('ready', function (Discord $discord) {
    echo "o ursão acordou pronto pra mais uma delícia vaiinnn" . PHP_EOL;
    echo "logado como: {$discord->username}#{$discord->discriminator}" . PHP_EOL;
    echo "sempre pronto pra se deliciar no php!" . PHP_EOL;
    
    $activity = $discord->factory(\Discord\Parts\User\Activity::class, [
        'name' => 'é essa a peça que você queria?',
        'type' => \Discord\Parts\User\Activity::TYPE_GAME
    ]);
    $discord->updatePresence($activity);
});

// ------------------------------------------------------------
// O Olhar do Caçador
// ------------------------------------------------------------
$discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {
    
    if ($message->author->bot) {
        return;
    }

    echo "Mensagem de {$message->author->username}: {$message->content}" . PHP_EOL;

    // --------------------------------------------------------
    // COMANDO: !ping
    // --------------------------------------------------------
    if ($message->content === '!ping') {
        echo "pong!" . PHP_EOL;
        $message->reply("🏓 Pong! Latência: **{$discord->heartbeatLatency}ms**");
        return;
    }

    // --------------------------------------------------------
    // COMANDO: !serverinfo
    // --------------------------------------------------------
    if ($message->content === '!serverinfo') {
        if ($message->channel->guild) {
            $guild = $message->channel->guild;
            $message->reply("Servidor: **{$guild->name}**\nMembros: **{$guild->member_count}**");
        } else {
            $message->reply("Comando só funciona em servidores.");
        }
        return;
    }

    // --------------------------------------------------------
    // 🍊 COMANDOS DO PAI DE FAMÍLIA 🍊
    // --------------------------------------------------------
    
    if (strpos(strtolower($message->content), '!jailson') !== false) {
        $frases = [
            "🍊 Ai, que delícia, cara!",
            "🍊 Jailson Mendes, o Pai de Família, abençoa este chat!",
            "🍊 Tá com medo de levar uma surra? Não, né?",
            "🍊 Ursos Grandes, Peludos e Mansos - O Clássico!"
        ];
        $message->reply($frases[array_rand($frases)]);
        return;
    }
    
    if (strpos(strtolower($message->content), '!suco') !== false) {
        $message->reply("🍊 Aqui está seu suco de laranja, Pai de Família!\n*Ai, que delícia, cara!*");
        return;
    }
    
    if (strpos(strtolower($message->content), '!delicia') !== false) {
        $message->reply("🍊 QUE DELÍCIA, CARA!\n- Jailson Mendes, o eterno Pai de Família (✝ 2018)");
        return;
    }
    
    if (strpos(strtolower($message->content), '!pai') !== false) {
        $message->reply(
            "🍊 O PAI DE FAMÍLIA 🍊\n" .
            "Nome: Jocione Mendonça\n" .
            "Naturalidade: Iati, Pernambuco\n" .
            "Legado: O suco de laranja mais famoso do Brasil\n" .
            "*'Ser homossexual não é aberração nem doença' - Jailson Mendes*"
        );
        return;
    }
    
    if ($message->content === '!help' || $message->content === '!ajuda') {
        $message->reply(
            "🍊 **Comandos do Pai de Família** 🍊\n\n" .
            "**!ping**: Teste a latência da internet chamando o Jailson para uma partida de ping-pong!\n" .
            "**!serverinfo**: O Urso Manso te oferecerá as principais informações do servidor.\n" .
            "**!jailson**: Frases aleatórias e famosas do Jailson Mendes.\n" .
            "**!suco**: Refresque-se e relaxe com o suco de laranja oferecido de antemão pelo Pai de Família. 🍊\n" .
            "**!delicia**: Frase mais famosa já dita pelo Pai de Família.\n" .
            "**!pai**: Breve biografia do Jailson.");
        return;

    };
});

$discord->run();