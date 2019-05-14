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
        $response = $this->twitch->getStreamsApi()->getStreams();
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Get a user's ID from their username
     *
     * @param string $username
     * @return array|null
     * @throws GuzzleException
     */
    public function getUserId(string $username): ?array
    {
        $response = $this->twitch->getUsersApi()->getUserByUsername($username);

        $responseData = json_decode($response->getBody()->getContents(), true)['data'];

        if (sizeof($responseData) > 0) {
            return json_encode([
                'username' => $username,
                'id' => $responseData[0]['id']
            ]);
        }

        return null;
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
