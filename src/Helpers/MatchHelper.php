<?php

namespace B3none\League\Helpers;

class MatchHelper extends BaseHelper
{
    /**
     * @param string $matchId
     * @return array|null
     */
    public function getMatchPlayers(string $matchId): ?array
    {
        $query = $this->db->query('
            SELECT sql_matches_scoretotal.*, sql_matches.*
            FROM sql_matches_scoretotal 
            INNER JOIN sql_matches ON sql_matches_scoretotal.match_id = sql_matches.match_id
            WHERE sql_matches_scoretotal.match_id = :matchId 
            ORDER BY sql_matches.score DESC
        ', [
            ':matchId' => $matchId,
        ]);

        $matchPlayers = $query->fetchAll();

        return $matchPlayers ? $this->formatMatchPlayers($matchPlayers) : null;
    }

    /**
     * @param array $players
     * @return array
     */
    protected function formatMatchPlayers(array $players): array
    {
        $formattedPlayers = [
            'ct' => [
                'players' => [],
                'name' => 'Counter-Terrorists',
                'score' => 0
            ],
            't' => [
                'players' => [],
                'name' => 'Terrorists',
                'score' => 0
            ],
        ];

        foreach ($players as $player) {
            if ($player['team'] == 2) {
                $formattedPlayers['ct']['players'][] = $this->formatMatchPlayer($player);
                $formattedPlayers['ct']['score'] = $player['team_2'];

                if (array_key_exists('teamname_2', $player) && !empty($player['teamname_2'])) {
                    $formattedPlayers['ct']['name'] = $player['teamname_2'];
                }
            } elseif ($player['team'] == 3) {
                $formattedPlayers['t']['players'][] = $this->formatMatchPlayer($player);
                $formattedPlayers['t']['score'] = $player['team_3'];

                if (array_key_exists('teamname_3', $player) && !empty($player['teamname_3'])) {
                    $formattedPlayers['t']['name'] = $player['teamname_3'];
                }
            }
        }

        return $formattedPlayers;
    }

    /**
     * @param array $player
     * @return array
     */
    protected function formatMatchPlayer(array $player): array
    {
        if ($player['kills'] > 0 && $player['deaths'] > 0) {
            $player['kdr'] = round(($player['kills'] / $player['deaths']), 2);
        } else {
            $player['kdr'] = 0;
        }

        $player['name'] = htmlspecialchars(substr($player['name'], 0, 32));

        return $player;
    }
}
