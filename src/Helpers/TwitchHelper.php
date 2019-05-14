<?php

namespace B3none\League\Helpers;

use GuzzleHttp\Exception\GuzzleException;
use NewTwitchApi\HelixGuzzleClient;
use NewTwitchApi\NewTwitchApi;

class TwitchHelper extends BaseHelper
{
    /**
     * @var NewTwitchApi
     */
    protected $twitch;

    /**
     * DiscordHelper constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $helixGuzzleClient = new HelixGuzzleClient(env('TWITCH_CLIENT_ID'));
        $this->twitch = new NewTwitchApi($helixGuzzleClient, env('TWITCH_CLIENT_ID'), env('TWITCH_CLIENT_SECRET'));
    }

    public function test()
    {
        try {
            $response = $this->twitch->getUsersApi()->getUsersFollows(null, '124125274');

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            // Handle error appropriately for your application
            die("error :/");
        }
    }
}
