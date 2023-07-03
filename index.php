<?php
    session_start();
    $note = "";
    if (isset($_POST['prihlaseni'])) {
        $jmeno = $_POST['jmeno'];
        $heslo = sha1($_POST['heslo']);

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $mysqli = new mysqli("localhost", "root", "", "uzivatele");
    
        $sql = "SELECT jmeno FROM uzivatele WHERE heslo = '$heslo'";
        $result = $mysqli->query($sql);

        if($result->num_rows == 1) {
            $_SESSION['prihlasen'] = true;
            $_SESSION['jmeno'] = $jmeno;
            $datum = date("Y-m-d H:i:s");

            $sql = "UPDATE uzivatele SET prihlaseni = '{$datum}' WHERE heslo = '{$heslo}'";
            $result = $mysqli->query($sql);

            header("Location: admin.php");
        } else {
            $note = "<div class='col-6 alert alert-danger' role='alert'>Špatné jméno nebo heslo</div>";
        }
    
        $mysqli->close();
    }
?><!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uživatelé</title>    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">        
        <div class="row">
            <div class="col-3"></div>
            <div class="col-6"><h1>Přihlášení</h1></div>
            <div class="col-3"></div>
        </div>
        <div class="row">
            <div class="col-3"></div><?= $note ?><div class="col-3"></div>
        </div>
        <div class="row">
            <div class="col-3"></div>
            <div class="col-6">
                <form action="" method="post">
                    <div class="row mb-3">
                        <label for="inputJmeno" class="col-sm-4 col-form-label">Uživatelské jméno: </label>
                        <div class="col-sm-8">
                            <input type="text" name="jmeno" class="form-control" id="inputJmeno">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputHeslo" class="col-sm-4 col-form-label">Heslo:</label>
                        <div class="col-sm-8">
                            <input type="password" name="heslo" class="form-control" id="inputHeslo">
                        </div>
                    </div>
                    <input type="submit" name="prihlaseni" class="col-12 btn btn-primary" value="Přihlásit">        
                </form>
            </div>
            <div class="col-3"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>