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

    /**
     * Get a twitch user's followers.
     *
     * @param string $userId
     * @return mixed
     * @throws GuzzleException
     */
    public function getFollowers(string $userId)
    {
        $response = $this->twitch->getUsersApi()->getUsersFollows(null, $userId);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Get a twitch user's followers.
     *
     * @param string $userId
     * @return mixed
     * @throws GuzzleException
     */
    public function getFollowing(string $userId)
    {
        $response = $this->twitch->getUsersApi()->getUsersFollows($userId);

        return json_decode($response->getBody()->getContents(), true);
    }
}
