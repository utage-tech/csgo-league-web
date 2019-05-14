<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\TwitchHelper;

class TwitchController
{
    /**
     * @var TwitchHelper
     */
    protected $twitchHelper;

    public function __construct()
    {
        $this->twitchHelper = new TwitchHelper();
    }

    public function test()
    {
        echo json_encode($this->twitchHelper->test());
    }
}
