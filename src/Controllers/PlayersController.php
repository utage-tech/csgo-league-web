<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\ExceptionHelper;
use B3none\League\Helpers\PlayersHelper;
use Exception;

class PlayersController extends BaseController
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
     * Get players.
     *
     * @param string|null $page
     * @return string
     */
    public function getPlayers(?string $page = null): string
    {
        try {
            $limit = env('PLAYERS_PAGE_LIMIT');
            $page = $page ?? 1;

            if ($page != (int)$page) {
                throw new Exception('Please only pass an integer to the page parameter');
            }

            // If the page number is less that 1 then redirect them to page 1.
            if ($page < 1) {
                response()->redirect('/players/1');
            }

            $totalPlayers = $this->playersHelper->getPlayersCount();
            $totalPages = ceil($totalPlayers / $limit);

            if ($page > $totalPages) {
                response()->redirect('/players/' . $totalPages);
            }

            $players = $this->playersHelper->getPlayers($page);

            return $this->twig->render('players.twig', [
                'nav' => [
                    'active' => 'players',
                    'loggedIn' => $this->steam->loggedIn(),
                    'user' => $this->authorisedUser
                ],
                'baseTitle' => env('BASE_TITLE'),
                'title' => 'Players',
                'players' => $players,
                'pagination' => [
                    'currentPage' => $page,
                    'totalPages' => ceil($totalPlayers / $limit),
                    'link' => 'players'
                ]
            ]);
        } catch (Exception $exception) {
            ExceptionHelper::handle($exception);
        }
    }

    /**
     * @return string
     */
    public function postIndex(): string
    {
        try {
            $search = input()->post('search')->getValue();

            if (!$search) {
                response()->redirect('/players');
            }

            $players = $this->playersHelper->searchPlayers($search);

            return $this->twig->render('players.twig', [
                'nav' => [
                    'active' => 'players'
                ],
                'players' => $players,
                'searchedValue' => $search,
                'title' => 'Players',
                'baseTitle' => env('BASE_TITLE'),
                'description' => env('DESCRIPTION'),
            ]);
        } catch (Exception $exception) {
            ExceptionHelper::handle($exception);
        }
    }
}
