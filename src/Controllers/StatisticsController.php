<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\PlayersHelper;

class StatisticsController extends BaseController
{
    /**
     * @var PlayersHelper
     */
    protected $playersHelper;

    /**
     * MatchController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->playersHelper = new PlayersHelper();
    }

    /**
     * @param string $steamId
     * @return string
     */
    public function getProfile(string $steamId): string
    {
        try {
            $player = $this->playersHelper->getPlayer($steamId);

            return $this->twig->render('profile.twig', [
                'player' => $player,
                'baseTitle' => env('BASE_TITLE'),
            ]);
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');

            echo json_encode([
            'status' => 500
            ]);

            die;
        }
    }
}
