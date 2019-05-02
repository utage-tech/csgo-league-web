<?php
require(__DIR__ . '/../../app/config.php');

$page_number = isset($_GET['page']) ? $_GET['page'] : 0;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $page_title; ?></title>

    <?php require('Partials/styles.twig'); ?>
</head>

<body>
    <div class="matches container" style="margin-top: 20px;">
        <?php require(__DIR__ . '/Partials/head.twig'); ?>

        <form method="post">
            <div class="search-container center" style="width: 70%;">
                <input type="text" name="search-bar" placeholder="Search Match ID, Player Name or SteamID64" class="search-input">
                <button class="btn btn-light search-btn" type="submit" name="Submit"> <i class="fa fa-search"></i></button>
            </div>
        </form>

<?php

    $result = $conn->query($sql);

    if (!!$result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $half = ($row['team_2'] + $row['team_3']) / 2;

            if ($row['team_2'] > $half) {
                $image = 'ct_icon.png';
            } elseif ($row['team_2'] == $half && $row['team_3'] == $half) {
                $image = 'tie_icon.png';
            } else {
                $image = 't_icon.png';
            }

            $map_img = array_search($row['map'], $maps);

            echo '
            <div class="match d-none" style="margin-bottom: 35px">        
                <a href="scoreboard.php?id='.$row['match_id'].'">
                    <div class="card match-card center" data-bs-hover-animate="pulse" style="margin-top:35px;"><img class="card-img w-100 d-block matches-img rounded-borders" style="background-image:url(&quot;'.$map_img.'&quot;);height:150px;">
                        <div class="row card-img-overlay">
                            <h4 class="text-white col-4" style="font-size:70px;margin-top:15px;">'.$row['team_2'].':'.$row['team_3'].'</h4>
                            <h4 class="text-white col-4 timestamp" style="text-align: center; font-size:30px;margin-top:15px;">'.$row['timestamp'].'</h4>
                            <div class="col-4">
                                <img class="float-right" src="assets/img/icons/'.$image.'" style="width:110px;">
                            </div>
                        </div>
                    </div>
                </a>
            </div>';
        }
    } else {
        echo '<h1 style="margin-top:20px;text-align:center;">No Results!</h1>';
    }

    if (!isset($_POST['Submit'])) {
        $sql_pages = 'SELECT COUNT(*) FROM sql_matches_scoretotal';
        $result_pages = $conn->query($sql_pages);
        $row_pages = $result_pages->fetch_assoc();

        $total_pages = ceil($row_pages['COUNT(*)'] / $limit);

        if ($total_pages > 1) {
            echo '
            <nav style="margin-top:30px;width:80%;" class="center">
                <ul class="pagination">';

                if ($page_number == 1) {
                    echo '
                    <li class="page-item disabled">
                        <span class="page-link">
                            Previous
                        </span>
                    </li>';
                } else {
                    $past_page = $page_number - 1;
                    echo '
                    <li class="page-item">
                        <a class="page-link" href="?page=' . $past_page . '">
                            Previous
                        </a>
                    </li>';
                }

                for ($i = max(1, $page_number - 2); $i <= min($page_number + 4, $total_pages); $i++) {
                    if ($i == $page_number) {
                        echo '
                        <li class="page-item active">
                            <span class="page-link">
                                ' . $i . '
                                <span class="sr-only">
                                    (current)
                                </span>
                            </span>
                        </li>';
                    } else {
                        echo '
                        <li class="page-item">
                            <a class="page-link" href="?page=' . $i . '">
                                ' . $i . '
                            </a>
                        </li>';
                    }
                }
                if ($page_number == $total_pages) {
                    echo '
                    <li class="page-item disabled">
                        <span class="page-link">
                            Next
                        </span>
                    </li>';
                } else {
                    $next_page = $page_number + 1;
                    echo '
                    <li class="page-item">
                        <a class="page-link" href="?page=' . $next_page . '">
                            Next
                        </a>
                    </li>';
                }
                echo '
                </ul>
            </nav>';
        }
    }

    require('Partials/scripts.twig');
?>
</body>

</html>