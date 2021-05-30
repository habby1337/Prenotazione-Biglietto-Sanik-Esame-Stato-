<?php


//Include config and connection file
include "./config.php";




// Avvio la sessione
session_start();

// Controllo se l'utente è loggato sennò lo faccio loggare
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: accedi.php");
    exit;
}





?>
<!doctype html>
<html lang="it">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Compra biglietto</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Maturità</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="comprabiglietti.php">Compra bigletto</a>
                    </li>
                </ul>
            </div>
            <div class="d-flex">
                <p class="navbar-brand my-auto"><a href="esci.php" class="btn  btn-outline-danger">Esci</a></p>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <!-- <h1 class="text-center">Seleziona il biglietto</h1>
        <hr /> -->
        <div style="width: 100%; height: 25px; border-bottom: 1px solid black; text-align: center">
            <span style="font-size: 30px; background-color: #F3F5F6; padding: 0 10px;">
                Seleziona il biglietto
                <!--Padding is optional-->
            </span>
        </div>
        <div class="container mt-5">
            <div class="row row-cols-4 ">
                <?php
                $sql = "SELECT *, DATE(data) as giorno, DATE_FORMAT(TIME(data), '%H:%i') as orario, partita.id as id_partita, sede.id as id_sede FROM partita JOIN sede ON partita.sede=sede.id WHERE data > CURRENT_TIMESTAMP";
                $partite = $mysqli->query($sql);

                if ($partite->num_rows > 0) {
                    while ($partita = $partite->fetch_assoc()) {

                        echo '
                                <div class="col mt-3">
                                    <div class="card">
                                        <h5 class="card-header text-center">' . $partita['Team1'] . ' <span class="text-info">vs</span> ' . $partita['Team2'] . ' </h5>
                                        <div class="card-body">
                                            <h5 class="card-title text-muted mb-4">Il ' . $partita['giorno'] . ' alle ' . $partita['orario'] . '</h5>
                                            <p class="card-text">Indirizzo: <b>' . $partita['indirizzo'] . '</b></p>
                                            <p class="card-text">Posti spettatori: <b>' . $partita['numero_spettatori'] . '</b></p>
                                            <div class="text-center d-grid gap-2">
                                            <a data-bs-toggle="modal" data-bs-target="#acquistoBiglietto" data-table=\'' . json_encode($partita) . '\' class="btn btn-outline-success">Acquista</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        ';
                    }
                } else {
                    echo '
                    <div class="card text-white bg-danger mb-3 position-absolute top-50 start-50 translate-middle">
                        <div class="card-header">
                            Nessun biglietto.
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Purtroppo non ci sono biglietti disponibili :(</h5>
                            <p class="card-text">Attendi la prossima stagione di tornei.</p>
                            
                        </div>
                        <hr />
                    </div>

                    ';
                }
                ?>
            </div>
        </div>
    </div>
    <!-- Modal acquisto biglietto -->
    <div class="modal fade" id="acquistoBiglietto" tabindex="-1" aria-labelledby="acquistoBiglietto" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Acquisto Biglietto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

                        <!-- FORM NASCOSTO ALL'UTENTE -->
                        <input type="text" class="form-control visually-hidden" name="query" value="buy">
                        <input type="number" class="form-control visually-hidden" id="id_spettatore" name="id_spettatore" value="<?php echo $_SESSION['id_spettatore']; ?>">
                        <input type="number" class="form-control visually-hidden" id="id_partita" name="id_partita">
                        <input type="number" class="form-control visually-hidden" id="id_sede" name="id_sede">

                        <!-- FORM VISIBILE -->
                        <div class="mb-3 row">
                            <label for="spettatore" class="col-sm-2 col-form-label">Spettatore:</label>
                            <div class="col-sm-10">
                                <input readonly type="text" class="form-control-plaintext" id="spettatore" value="<?php echo $_SESSION['nome'] . ' ' . $_SESSION['cognome']; ?>">
                            </div>
                            <div id="spettatoreHelp" class="form-text">Questo è il nome dello spettatore per cui è generato il biglietto.</div>
                        </div>


                        <div class="mb-3 row">
                            <label for="email" class="col-sm-2 col-form-label">Indirizzo email:</label>
                            <div class="col-sm-10">
                                <input readonly type="email" class="form-control-plaintext" id="email" value="<?php echo $_SESSION['email']; ?>">
                            </div>
                            <!-- <div id="emailHelp" class="form-text">Non condivideremo con nessuno la tua email.</div> -->
                        </div>

                        <div class="mb-3 row">
                            <label for="numero_telefono" class="col-sm-2 col-form-label">Numero:</label>
                            <div class="col-sm-10">
                                <input readonly type="text" class="form-control-plaintext" id="numero_telefono" value="<?php echo $_SESSION['numero_telefono']; ?>">
                            </div>
                            <!-- <div id="numerHelp" class="form-text">Non condivideremo con nessuno il tuo numero di telefono.</div> -->
                        </div>
                        <hr>
                        <div class="mb-3 row">
                            <label for="partita" class="col-sm-2 col-form-label">Partita:</label>
                            <div class="col-sm-10">
                                <input readonly type="text" class="form-control-plaintext" id="partita">
                            </div>
                            <!-- <div id="numerHelp" class="form-text">Non condivideremo con nessuno il tuo numero di telefono.</div> -->
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <label for="partita" class="col-sm-2 col-form-label">Quando:</label>
                            <div class="col-3">
                                <input type="date" id='giorno' readonly class="form-control">
                            </div>
                            <span class="input-group-text">@</span>
                            <div class="col-3">
                                <input type="time" id='orario' readonly class="form-control" ">
                            </div>
                        </div>

                        <div class=" mb-3 row">
                                <label for="indirizzo" class="col-sm-2 col-form-label">Dove:</label>
                                <div class="col-sm-10">
                                    <input readonly type="text" class="form-control-plaintext" id="indirizzo">
                                </div>
                                <!-- <div id="numerHelp" class="form-text">Non condivideremo con nessuno il tuo numero di telefono.</div> -->
                            </div>
                            <div class=" mb-3 row">
                                <label for="costo" class="col-sm-2 col-form-label">Costo:</label>
                                <div class="col-sm-10">
                                    <input readonly type="currency" class="form-control-plaintext" id="costo" name="costo" value="50,00">
                                </div>
                                <!-- <div id="numerHelp" class="form-text">Non condivideremo con nessuno il tuo numero di telefono.</div> -->
                            </div>
                            <hr>

                            <!-- <div class=" mb-3 row">
                                <label for="orario" class="col-sm-2 col-form-label">Orario:</label>
                                <div class="col-sm-10">
                                    <input readonly type="time" class="form-control-plaintext" id="orario">
                                </div>

                            </div> -->

                            <div class="mb-3 form-check">
                                <input required type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Ho letto e rispetto tutte le <a href="#">norme di comportamento</a> per poter vedere la partita.</label>
                                <div class="invalid-feedback">
                                    Devi accettare prima di proseguire.
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Chiudi</button>
                            <button type="submit" class="btn btn-success">Acquista</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="confirmModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Biglietto acquistato!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Hey, il biglietto è stato acquistato, lo troverai nella tua dashboard!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Va bene</button>
                    <a href="dashboard.php" class="btn btn-success">Vai alla dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="alreadyBuy" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Biglietto già acquistato :(</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Hey, il biglietto che hai scelto è stato già acquistato, lo trovi nella tua dashboard!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Va bene</button>
                    <a href="dashboard.php" class="btn btn-warning">Vai alla dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="wentWrong" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Qualcosa è andato storto :(</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Oops, mi dispiace veramente tanto ma qualcosa è andato storto..
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Va bene</button>
                    <a href="dashboard.php" class="btn btn-success">Vai alla dashboard</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    -->
    <script>
        //Passa i dati dalla table al Modals
        $('#acquistoBiglietto').on('show.bs.modal', function(e) {
            var informazioni = $(e.relatedTarget).data('table');

            $(this).find('#id_partita').val(informazioni['id_partita']);
            $(this).find('#id_sede').val(informazioni['id_sede']);

            $(this).find('#partita').val(informazioni['Team1'] + ' vs ' + informazioni['Team2']);
            $(this).find('#orario').val(informazioni['orario']);
            $(this).find('#giorno').val(informazioni['giorno']);
            $(this).find('#indirizzo').val(informazioni['indirizzo']);
        })
    </script>

    <?php


    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&  $_POST['query'] == 'buy') {

        // var_dump($_POST);
        $data = json_encode($_POST);

        $blob = file_get_contents('https://chart.googleapis.com/chart?cht=qr&chs=500x500&chl=' . $data);

        // var_dump(base64_encode($blob));
        // echo $resutl;
        // echo '<img src="' . $resutl . '" class="img-fluid" alt="...">';
        // $sql = "INSERT INTO biglietto";
        // echo '<img src="data:image/png;base64,' . base64_encode($blob) . '"/>';

        // $sql = "INSERT INTO biglietto (costo, partita, sede, spettatore, qr) VALUES (" . $_POST['costo'] . ", " . $_POST['id_partita'] . ", " . $_POST['id_sede'] . ", " . $_POST['id_spettatore'] . ", " . base64_encode($blob) . ")";
        $sql = "SELECT * FROM biglietto WHERE partita=" . $_POST['id_partita'] . " AND spettatore=" . $_POST['id_spettatore'] . ";";

        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            echo '<script type="text/javascript">';
            echo '$(document).ready(function() {';
            echo '$("#alreadyBuy").modal("show");';
            echo '});';
            echo '</script>';
        } else {
            $sql = "INSERT INTO biglietto (costo, partita, sede, spettatore, qr) VALUES (?, ?, ?, ?, ?);";

            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("diiis", $costo, $id_partita, $id_sede, $id_spettatore, $qr);

                $costo = $_POST['costo'];
                $id_partita = $_POST['id_partita'];
                $id_sede = $_POST['id_sede'];
                $id_spettatore = $_POST['id_spettatore'];
                $qr = base64_encode($blob);



                //Esegui il modal con scritto biglietto acquistato.
                if ($stmt->execute()) {
                    echo '<script type="text/javascript">';
                    echo '$(document).ready(function() {';
                    echo '$("#confirmModal").modal("show");';
                    echo '});';
                    echo '</script>';
                } else {
                    echo '<script type="text/javascript">';
                    echo '$(document).ready(function() {';
                    echo '$("#wentWrong").modal("show");';
                    echo '});';
                    echo '</script>';
                }
                $stmt->close();
            }
        }
    }

    ?>

</body>