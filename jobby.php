<?php

require_once __DIR__ . '/vendor/autoload.php';

$jobby = new Jobby\Jobby();

$jobby->add('UpdatePlayers', [
    'closure'  => function() {
        $playersHelper = new \B3none\League\Helpers\PlayersHelper();
        $playersHelper->updatePlayers();
    },

    'schedule' => '0 * * * *',
]);

$jobby->run();