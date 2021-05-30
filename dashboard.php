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


//echo 'Ciao ' . $_SESSION['nome'] . '.<br>';
//echo 'il tuo id è ' . $_SESSION['id'] . '.<br>';
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
                        <a class="nav-link active" aria-current="page" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="comprabiglietti.php">Compra bigletto</a>
                    </li>


                </ul>
            </div>
            <div class="d-flex">
                <p class="navbar-brand my-auto"><a href="esci.php" class="btn  btn-outline-danger">Esci</a></p>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-9 col-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Data</th>
                            <th scope="col">Partita</th>
                            <th scope="col">Costo</th>
                            <th scope="col">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $sql = "SELECT *, DATE(data) as giorno, DATE_FORMAT(TIME(data), '%H:%i') as orario, partita.id as id_partita FROM biglietto JOIN partita ON biglietto.partita=partita.id WHERE spettatore=" . $_SESSION['id_spettatore'] . ";";
                        $biglietti = $mysqli->query($sql);

                        if ($biglietti->num_rows > 0) {
                            while ($biglietto = $biglietti->fetch_assoc()) {
                                echo '
                            <tr class="align-middle">
                                <th scope="row">' . $biglietto['id'] . '</th>
                                <td>' . $biglietto['giorno'] . ' ' . $biglietto['orario'] . '</td>
                                <td>' . $biglietto['Team1'] . ' vs ' . $biglietto['Team2'] . '</td>
                                <td>' . $biglietto['costo'] . '</td>
                                <td>
                                    <a href="#" data-table=\'' . json_encode($biglietto, JSON_UNESCAPED_SLASHES) . '\' class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#viewTicket" title="Visualizza biglietto"><i class="far fa-eye"></i></a>
                                    <a href="#" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Modifica dati biglietto"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="btn btn-danger"  title="Chiedi Rimborso"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>';
                            }
                        } else {
                            echo '
                            <tr class="align-middle table-danger text-cente position-relative ">
                            <th scope="row" colspan="5"  >Non è stato acquistato ancora nessun biglietto.</th>
                            </tr>';
                        }

                        ?>

                    </tbody>
                </table>

            </div>
            <div class="col-3">
                <div class="card text-center border-light">
                    <div class="card-header">
                        Profilo
                    </div>
                    <div class="card-body ">
                        <img src="https://www.cdcvillamaria.it/wp-content/uploads/2016/09/default-user-image.png" class="img-thumbnail rounded-circle " width="150" height="150" alt="User profile picture">
                        <h5 class="card-title"><?php echo $_SESSION['nome'] . ' ' . $_SESSION['cognome']; ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $_SESSION['email'] . ' <br> ' . $_SESSION['numero_telefono']; ?></h6>
                        <p class="card-text">




                        </p>
                        <a href="#" class="btn btn-outline-secondary">Modifica</a>
                    </div>
                    <div class="card-footer text-muted">
                        Utente dal: <?php echo $_SESSION['created_at']; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="viewTicket" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Visualizza biglietto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mx-auto my-auto">
                        <img src="#" class="img-thumbnail" alt="qr code" id="qrcode">

                        <hr>

                        <div class="mb-3 row">
                            <label for="partita" class="col-sm-2 col-form-label">Partita:</label>
                            <div class="col-sm-10">
                                <input readonly type="text" class="form-control-plaintext" id="partita">
                            </div>
                            <!-- <div id="numerHelp" class="form-text">Non condivideremo con nessuno il tuo numero di telefono.</div> -->
                        </div>
                        <div class="input-group input-group-sm mb-3">
                            <label for="partita" class="col-sm-2 col-form-label">Orario:</label>
                            <div class="col-3">
                                <input type="date" id='giorno' readonly class="form-control">
                            </div>
                            <span class="input-group-text">@</span>
                            <div class="col-3">
                                <input type="time" id='orario' readonly class="form-control" ">
                            </div>
                        </div>


                    </div>
                </div>
                <div class=" modal-footer">
                                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Chiudi</button>
                                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
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
</body>
<script>
    $('#viewTicket').on('show.bs.modal', function(e) {
        var informazioni = $(e.relatedTarget).data('table');
        // var obj = JSON.parse($(e.relatedTarget).data('table'))
        var qr = informazioni['qr'];



        console.log(informazioni);
        console.log(informazioni['costo']);
        console.log(informazioni['qr']);

        $(this).find('#qrcode').attr('src', 'data:image/png;base64,' + informazioni['qr']);

        // $(this).find('#id_partita').val(informazioni['id_partita']);
        // $(this).find('#id_sede').val(informazioni['id_sede']);

        $(this).find('#partita').val(informazioni['Team1'] + ' vs ' + informazioni['Team2']);
        $(this).find('#orario').val(informazioni['orario']);
        $(this).find('#giorno').val(informazioni['giorno']);
        // $(this).find('#indirizzo').val(informazioni['indirizzo']);
    })
</script>

</html>