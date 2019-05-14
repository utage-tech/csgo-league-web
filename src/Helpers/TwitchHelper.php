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

        $helixGuzzleClient = new HelixGuzzleClient();
        $this->twitch = new NewTwitchApi($helixGuzzleClient, env('TWITCH_CLIENT_ID'), env('TWITCH_CLIENT_SECRET'));
    }

    public function test()
    {
        try {
            // Make the API call. A ResponseInterface object is returned.
            $response = $this->twitch->getUsersApi()->getUserByUsername('b3none');
            echo $response->getBody()->getContents();
            die;
            // Get and decode the actual content sent by Twitch.
            $responseContent = json_decode($response->getBody()->getContents());

            return $responseContent->data[0];
        } catch (GuzzleException $e) {
            // Handle error appropriately for your application
            die("error :/");
        }
    }
}
