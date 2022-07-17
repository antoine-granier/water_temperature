<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Temperature Gironde</title>
    <link rel="stylesheet" href="style.css"/>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <div class="nav-bar">
        <div class="title">
            <ion-icon name="water"></ion-icon>
            Water Temperature
        </div>
        <img class="dark-mod" src="images/day-and-night.png" alt="Dark Mod">
    </div>
    <div class="swiper mySwiper">
        <div class="ocean">
            <div class="wave"></div>
            <div class="wave"></div>
        </div>
        <div class="swiper-wrapper">
    <?php
        //Recovery of the codes of the measuring stations
        $json = file_get_contents("https://hubeau.eaufrance.fr/api/v1/temperature/station?code_departement=33&format=json&pretty");
        if(!$json) {
            echo "<h2>Une erreur est survenue. Vérifier votre connexion...</h2>";
        } else {
            $result = json_decode($json, true);
            $nb_station = $result["count"];
            $station_code = array();
            for($i = 0; $i < $nb_station; $i++) {
                array_push($station_code, $result["data"][$i]["code_station"]);
            }
            foreach($station_code as $code) {
                //Recovery of the temperature and infomartions about stations
                $json = file_get_contents("https://hubeau.eaufrance.fr/api/v1/temperature/chronique?code_station=".$code."&size=1&sort=desc&pretty");
                $result = json_decode($json, true);
                $data = $result["data"][0];
                echo "<div class=\"swiper-slide\" id=\"".$code."\"><div class=\"station_card\"><h2 class=\"libelle-station\">".explode(' à ', $data["libelle_station"])[0]."</h2><p class=\"temperature\"><ion-icon name=\"thermometer-outline\"></ion-icon> ".substr((string) $data["resultat"], 0, 2)."°C</p><p class=\"adress\">".$data["libelle_commune"].", ".$data["code_commune"]."</p></div></div>";
            }
            //var_dump($station_code);
        }
    ?>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="popup">
        <h3 class="station-title"></h3>
        <ion-icon class="close-btn close-btn-dark" name="close-outline"></ion-icon>
        <div class="main-popup">
                <div class="wait"><div></div><div></div><div></div><div></div></div>
                <div class="chart"></div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script src="app.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        if(screen.whidth < 1200 && screen.width > 894) {
            var swiper = new Swiper(".mySwiper", {
                slidesPerView: 3,
                spaceBetween: 50,
                centeredSlides: true,
                cssMode: true,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                mousewheel: true,
                keyboard: true,
            });
        } else if(screen.width > 1200) {
            var swiper = new Swiper(".mySwiper", {
                slidesPerView: 4,
                spaceBetween: 50,
                centeredSlides: true,
                cssMode: true,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                mousewheel: true,
                keyboard: true,
            });
        } else if(screen.width > 600) {
            var swiper = new Swiper(".mySwiper", {
                slidesPerView: 2,
                spaceBetween: 50,
                centeredSlides: true,
                cssMode: true,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                mousewheel: true,
                keyboard: true,
            });
        } else {
            var swiper = new Swiper(".mySwiper", {
                slidesPerView: 1.5,
                spaceBetween: 50,
                centeredSlides: true,
                cssMode: true,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                mousewheel: true,
                keyboard: true,
            });
        }
    </script>
</body>
</html>