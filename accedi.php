<?php

session_start();

//Controlla se l'utente è già loggato
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: dashboard.php");
    exit;
}

require_once "config.php";

//Dichiarazione
$email = $nome = $password = "";
$email_err = $nome_err = $password_err = $login_err = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Controllo se email vuoto
    if (empty(trim($_POST['email']))) {
        $email_err = "Perfavore inserisci un email.";
    } else {
        $email = trim($_POST['email']);
    }

    // Controlla se la password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }


    //Convalidazione credenziali
    if (empty($email_err) && empty($password_err)) {


        $sql = "SELECT id, nome, email, password FROM spettatore WHERE email = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $param_email);

            $param_email = $email;

            if ($stmt->execute()) {
                $stmt->store_result();

                //Controllo se email esiste
                if ($stmt->num_rows == 1) {

                    $stmt->bind_result($id, $nome, $email, $hashed_password);
                    error_log("Password:" . $password);
                    error_log("hashed_password:" . $hashed_password);
                    if ($stmt->fetch()) {


                        if (password_verify($password, $hashed_password)) {

                            session_start();

                            //Salvo dati di sessione
                            $_SESSION['loggedin'] = true;
                            $_SESSION['id'] = $id;
                            $_SESSION['nome'] = $nome;

                            header("location: dashboard.php");
                        } else {
                            //Password non valida, errore generico
                            $login_err = "email o password non valido.";
                        }
                    }
                } else {
                    //email non esistente, errore generico.
                    $login_err = "email o password non valido.";
                }
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
    <title>Login</title>
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
        <h2>Login</h2>
        <p>Perfavore inserisci le tue credenziali per accedere.</p>

        <?php
        if (!empty($login_err)) {
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Non hai un account? <a href="register.php">Crealo ora</a>.</p>
        </form>
    </div>
</body>

</html>