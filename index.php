<!doctype html>
<html lang="pt">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Marvel Characters</title>

    <link href="https://getbootstrap.com/docs/5.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <style>
        body{
            background: #111;
            color: #fff;
        }
        .corpo{
            position: relative;
            top: 80px;
        }
        .corpo img{
            border-left: 3px solid #fd3b3b;
            cursor: pointer;
            width: 100%;
        }
        .corpo p{
            position: relative;
            top: -50px;
            height: 50px;
            background: rgba(0, 0, 0, 0.8);
            font-size: 13px;
            margin-left: 3px;
            padding: 5px;
            text-align: right;
            text-shadow: 1px 1px 1px black;
        }
        .rodape{
            margin: 0 auto;
            padding: 30px;
        }
        .py-5 a{
            text-decoration: none;
        }
        .tela-1,.tela-2, .tela-3, .tela-4, .tela-5{
            height: auto;
            display: none;
            padding: 20px;
            text-align: right;
            background: linear-gradient(78deg, rgba(0,0,0,0),rgba(0,0,0,0.9),rgba(0,0,0,0));
            text-shadow: 1px 1px 1px black;
        }
    </style>
</head>
<body>
    <nav class="navbar fixed-top navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="?pg=0">
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/MarvelLogo.svg" alt="Marvel' API" width="100" height="40" class="d-inline-block align-top">
            </a>
            <form class="d-flex" control="GET">
                <input class="form-control me-2" name="search" type="search" placeholder="Pesquisar..." aria-label="Search">
            </form>
        </div>
    </nav>
    <div class="container">
        <div class="corpo row">
            <div class="col-12 tela-1"></div>
            <?php
            $ts = new DateTime();
            $ts = $ts->getTimestamp();
            $puK = "fe68bfd9e876f2bd17a1688dbaae58a3";
            $prK = "db341d8c9fa77cc2975c7544df49e42b46ce659e";
            $hash = MD5($ts.$prK.$puK);
            $buscador = 'characters';
            $procurador = isset($_GET['search']) ? 'nameStartsWith='.$_GET['search'].'&' : "";
            $pg = isset($_GET['pg']) ? ($_GET['pg'] >= 0 ? $_GET['pg'] : 0) : 0;

            $api = 'http://gateway.marvel.com/v1/public/'.$buscador.'?'.$procurador.'ts='.$ts.'&apikey='.$puK.'&hash='.$hash.'&offset='.$pg.'&limit=20';
            $api = file_get_contents($api,true);
            $api = json_decode($api,true);

            $cont = 0;
            $indt = 1;
            foreach($api['data']['results'] as $a){
                if($cont > 3){
                    $indt++;
                    $cont = 0;
                    echo '<div class="col-12 tela-'.$indt.'"></div>';
                }

                echo "<div class='col-3'>";
                    echo "<img alt='no-image' onclick=\"chamai(".$indt.", '".$a['name']."','".$a['thumbnail']['path']."','".strip_tags(addslashes(htmlspecialchars($a['description'])))."','".date("H:m d-m-Y",$a['modified'])."')\" onerror=\"this.onerror=null; this.src='http://i.annihil.us/u/prod/marvel/i/mg/b/40/image_not_available/portrait_incredible.jpg'\" src='".$a['thumbnail']['path']."/portrait_incredible.jpg'>";
                    echo "<p>".$a['name']."</p>";
                echo "</div>";
                $cont++;
            }
            ?>
        </div>
    </div>
    
    <footer class="text-muted py-5">
        <div class="container">
            <div class="btn-toolbar">
                <div class="btn-group rodape">
                    <?php
                    if(!isset($_GET['search'])){
                        if(isset($_GET['pg']) && $_GET['pg'] > 0){
                            echo '<a type="button" href="?pg='.($pg-20).'" class="btn btn-danger"><< Prev</a>';
                        }else{
                            echo '<button type="button" class="btn btn-danger" disabled="disabled"><< Prev</button>';
                        }
                        echo '<a type="button" href="?pg='.($pg+20).'" class="btn btn-danger">Next >></a>';
                    }
                    ?>
                </div>
            </div>
            <p class="float-end mb-1">
                <a href="#">Back to top</a>
            </p>
            <p class="mb-0">Open source project <a href="https://github.com/eryc23/marvel-api">Visit the Github</a> or read the <a href="https://developer.marvel.com/">getting started guide</a>.</p>
            <p class="mb-1">Marvel API © 2020</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        function chamai(pg,nome, thumb,desc, data){
            var desc = desc == "" ? "Description not found..." : desc;

            $('.tela-1, .tela-2, .tela-3, .tela-4, .tela-5').fadeOut();
            $('.tela-'+pg).slideDown();
            $('.tela-'+pg).html(`
            <div class="row">
                <div class="col-6">
                    <h1>${nome}</h1>
                    <h6>&#128336; ${data}</h6>
                    <span>${desc}</span><br><br>
                    <h6>Comics</h6>
                </div>
                <div class="col-6">
                    <img class="img-fluid float-start" onerror="this.onerror=null; this.src='http://i.annihil.us/u/prod/marvel/i/mg/b/40/image_not_available.jpg'" src="${thumb}.jpg">
                </div>
            </div>`);
        }
    </script>
</body>
</html>