<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\MatchesHelper;
use B3none\League\Helpers\PlayersHelper;
use B3none\League\Helpers\ProfileHelper;

class ProfileController extends BaseController
{
    /**
     * @var ProfileHelper
     */
    protected $profileHelper;

    /**
     * @var PlayersHelper
     */
    protected $playersHelper;

    /**
     * @var MatchesHelper
     */
    protected $matchesHelper;

    /**
     * MatchController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->profileHelper = new ProfileHelper();
        $this->playersHelper = new PlayersHelper();
        $this->matchesHelper = new MatchesHelper();
    }

    /**
     * @param string $steamId
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function getProfile(string $steamId): string
    {
        $player = $this->playersHelper->getPlayer($steamId);
        $matches = $this->matchesHelper->getPlayerMatches($steamId);

        return $this->twig->render('profile.twig', [
            'player' => $player,
            'matches' => $matches,
        ]);
    }
}