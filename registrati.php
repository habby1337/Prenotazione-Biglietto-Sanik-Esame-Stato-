<?php

//Include il file config
require_once "config.php";

//Definizione delle variabili con valori vuoti
$nome = $cognome = $numero = $data = $email = $password = $confirm_password = "";
$nome_err = $cognome_err = $numero_err = $data_err = $email_err = $password_err = $confirm_password_err = "";

//Processa data quando il form viene inviato
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    //Convalida email
    if (empty(trim($_POST['email']))) {
        $email_err = "Perfavore inserisci un email.";
    } else {
        //Preparo lo stmt
        $sql = "SELECT id FROM spettatore WHERE email = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            //bind del valore 
            $stmt->bind_param("s", $param_email);

            $param_email = trim($_POST['email']);

            //Esecuzione dello stmt
            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $email_err = "L'email è già in utilizzo!";
                } else {
                    $email = trim($_POST['email']);
                }
            } else {
                echo "Oops! Qualcosa è andato storto, contatta l'assistenza!";
            }
            //Chiudo la connessione
            $stmt->close();
        }
    }

    //Convalida nome
    if (empty(trim($_POST['nome']))) {
        $nome_err = "Perfavore inserisci il tuo nome.";
    } else {
        $nome = trim($_POST['nome']);
    }

    //Convalida Cognome
    if (empty(trim($_POST['cognome']))) {
        $cognome_err = "Perfavore inserisci il tuo cognome.";
    } else {
        $cognome = trim($_POST['cognome']);
    }

    //Convalida data
    if (empty(trim($_POST['data']))) {
        $data_err = "Perfavore inserisci la tua data di nascita.";
    } else {
        $data = strtotime(trim($_POST['data']));
    }

    //Convalida Cognome
    if (empty(trim($_POST['numero']))) {
        $numero_err = "Perfavore inserisci il tuo numero di telefono.";
    } else {
        $numero = trim($_POST['numero']);
    }


    //Convalida email
    if (empty(trim($_POST['email']))) {
        $email_err = "Perfavore inserisci la tua email.";
    } else {
        $email = trim($_POST['email']);
    }

    //Convalida password
    if (empty(trim($_POST['password']))) {
        $password_err = "Perfavore inserisci una password.";
    } elseif (strlen(trim($_POST['password'])) < 6) {
        $password_err = "La password deve minimo 6 caratteri.";
    } else {
        $password = trim($_POST['password']);
    }

    //Convalida della password di conferma
    if (empty(trim($_POST['confirm_password']))) {
        $confirm_password_err = "Perfavore conferma la password.";
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Le password sono diverse.";
        }
    }


    //Controllo degli errori di input prima di inserire nel db
    if (empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err) && empty($nome_err) && empty($cognome_err) && empty($data_err) && empty($numero_err)) {
        $sql = "INSERT INTO spettatore (nome, cognome, data_nascita, email, password, numero_telefono) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("sssssi", $param_nome, $param_cognome, $param_data_nascita, $param_email, $param_password, $param_numero);


            $param_nome = $nome;
            $param_cognome = $cognome;
            $param_data_nascita = $data;
            $param_numero = $numero;
            $param_email = $email;

            $param_password = trim(password_hash($password, PASSWORD_BCRYPT));
            error_log($param_password);

            if ($stmt->execute()) {
                header("location: accedi.php"); //Redirect alla page di login
            } else {
                echo "Oops! Qualcosa è andato storto, contatta l'assistenza!";
            }

            $stmt->close();
        }
    }
    $mysqli->close();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Registrazione</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif;
        }

        .wrapper {
            width: 350px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <h2>Registrazione</h2>
        <p>Perfavore inserisci i dati per creare un accoutn.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">


            <div class="form-group">
                <label>Nome</label>
                <input type="text" name="nome" class="form-control <?php echo (!empty($nome_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nome; ?>">
                <span class="invalid-feedback"><?php echo $nome_err; ?></span>
            </div>
            <div class="form-group">
                <label>Cognome</label>
                <input type="text" name="cognome" class="form-control <?php echo (!empty($cognome_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $cognome; ?>">
                <span class="invalid-feedback"><?php echo $cognome_err; ?></span>
            </div>
            <div class="form-group">
                <label>Data di nascita</label>
                <input type="date" name="data" class="form-control <?php echo (!empty($data_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $data; ?>">
                <span class="invalid-feedback"><?php echo $data_err; ?></span>
            </div>
            <div class="form-group">
                <label>Numero di telefono</label>
                <input type="text" name="numero" class="form-control <?php echo (!empty($numero_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $numero; ?>">
                <span class="invalid-feedback"><?php echo $numero_err; ?></span>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Conferma Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Registrati">
                <input type="reset" class="btn btn-secondary ml-2" value="Cancella">
            </div>
            <p>Già hai un account? <a href="login.php">Fai il login</a>.</p>
        </form>
    </div>
</body>

</html>