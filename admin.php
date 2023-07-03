<?php
    require '../../vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    session_start();
    if (!isset($_SESSION['prihlasen'])) {
        header("Location: index.php");
    }
    $jmeno = $_SESSION['jmeno'];
    $note  = "";

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $mysqli = new mysqli("localhost", "root", "", "uzivatele");

    if (isset($_POST['pridat'])) {
        if ($_POST['heslo'] == $_POST['heslo2']) {
            $nove_jmeno = $_POST['jmeno'];
            $nove_heslo = sha1($_POST['heslo']);
            $sql = "INSERT INTO uzivatele (jmeno, heslo) VALUES ('$nove_jmeno', '$nove_heslo')";
            $mysqli->query($sql); 
        } else {
            $note = "Zadaná hesla se neshodují";
        }       
    }

    if (isset($_POST['odhlasit'])) {
        session_destroy();
        header("Location: index.php");
    }

    if (isset($_POST['exportuj'])) {        

        $spreadsheet = new Spreadsheet();
        $Excel_writer = new Xlsx($spreadsheet);
          
        $spreadsheet->setActiveSheetIndex(0);
        $activeSheet = $spreadsheet->getActiveSheet();
          
        $activeSheet->setCellValue('A1', 'Jméno');
        $activeSheet->setCellValue('B1', 'Heslo');
        $activeSheet->setCellValue('C1', 'Přihlášení');
          
        $sql = "SELECT * FROM uzivatele";
        $result = $mysqli->query($sql);
          
        if($result->num_rows > 0) {
            $i = 2;
            while($row = $result->fetch_assoc()) {
                $activeSheet->setCellValue('A'.$i , $row['jmeno']);
                $activeSheet->setCellValue('B'.$i , $row['heslo']);
                $activeSheet->setCellValue('C'.$i , $row['prihlaseni']);
                $i++;
            }
        }
          
        $fileName = "data-uzivatelu-" . date('Y-m-d') . ".xlsx";        
        $content = "";
        try {
            $Excel_writer->save($fileName);
            $content = file_get_contents($fileName);
        } catch(Exception $e) {
            exit($e->getMessage());
        }
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=".$fileName);
        header('Cache-Control: max-age=0');
        unlink($fileName);
        exit($content);
    }

    if (!empty($note)) {
        $note = "<div class='row mb-3'><div class='col-sm-12 alert alert-danger' role='alert'>" . $note . "</div></div>";
    }

?><!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrace uživatelů</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">        
        <div class="row">
            <div class="col-1"></div>
            <div class="col-6">                
                <h1>Administrace</h1>                
            </div>
            <div class="col-4">
                <div class="row">
                    <div class="col-12"><h2>Přidání nového uživatele</h2></div>  
                </div>
            </div>
            <div class="col-1"></div>
        </div>
        <div class="row">
            <div class="col-1"></div>
            <div class="col-6">
                <table class="col-6 table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#Id</th>
                            <th>Jméno</th>
                            <th>Heslo</th>
                            <th>Přihlášen</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                <?php       
                
                    $sql = "SELECT * FROM uzivatele";
                    $result = $mysqli->query($sql);   
                    while ($row = $result->fetch_assoc()) {
                        $prihlaseni = (!empty($row['prihlaseni'])) ? substr($row['prihlaseni'], 8, 2) . "." . substr($row['prihlaseni'], 5, 2) . "." . substr($row['prihlaseni'], 0, 4) . " " . substr($row['prihlaseni'], 11, 8) : "";
                        echo("<tr><th>" . $row['id'] . "</th><td>" . $row['jmeno'] . "</td><td>" . $row['heslo'] . "</td><td class='date-small'>" . $prihlaseni . "</td></tr>");
                    }
                ?>
                    </tbody>
                </table>
                <form class="col-12" action="" method="post">
                    <input type="submit" name="exportuj" class="col-12 btn btn-success" value="Vyexportuj">
                </form>
            </div> 
            <div class="col-4">  
                <form action="" method="post">
                    <?= $note ?>
                    <div class="row mb-3">
                        <label for="inputJmeno" class="col-sm-6 col-form-label">Uživatelské jméno: </label>
                        <div class="col-sm-6">
                            <input type="text" name="jmeno" class="form-control" id="inputJmeno" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputHeslo" class="col-sm-6 col-form-label">Heslo:</label>
                        <div class="col-sm-6">
                            <input type="password" name="heslo" class="form-control" id="inputHeslo" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputHeslo2" class="col-sm-6 col-form-label">Heslo znovu:</label>
                        <div class="col-sm-6">
                            <input type="password" name="heslo2" class="form-control" id="inputHeslo2" required>
                        </div>
                    </div>                    
                    <input type="submit" name="pridat" class="col-12 btn btn-primary" value="Přidej nového uživatele">        
                </form>         
            </div>
            <div class="col-1"></div>
        </div>              
    </div>

    <form action="" method="post">
        <div id="aktualni_uzivatel">
            <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
            </svg>
            <h3><?= $jmeno?></h3>
            <input type="submit" name="odhlasit" value="Odhlásit" class="btn btn-primary">
        </div>        
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>