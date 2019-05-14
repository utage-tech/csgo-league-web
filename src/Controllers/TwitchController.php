<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\TwitchHelper;

class TwitchController
{
    /**
     * @var TwitchHelper
     */
    protected $twitchHelper;

    /**
     * TwitchController constructor.
     */
    public function __construct()
    {
        $this->twitchHelper = new TwitchHelper();
    }

    public function test()
    {
        return json_encode($this->twitchHelper->test());
    }
}
