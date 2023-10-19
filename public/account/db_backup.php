<?php
include 'controller/Constants.php';

$host = "localhost";
$username = "sharedigitalcard";
$password = "wg0qS9^6";
$database_name = "sharedigitalcard";

// Get connection object and set the charset
$conn = mysqli_connect($host, $username, $password, $database_name);
$conn->set_charset("utf8");

// Get All Table Names From the Database
$tables = array();
$sql = "SHOW TABLES";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_row($result)) {
    $tables[] = $row[0];
}

$sqlScript = "";
foreach ($tables as $table) {

    // Prepare SQLscript for creating table structure
    $query = "SHOW CREATE TABLE $table";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_row($result);

    $sqlScript .= "\n\n" . $row[1] . ";\n\n";


    $query = "SELECT * FROM $table";
    $result = mysqli_query($conn, $query);

    $columnCount = mysqli_num_fields($result);

    // Prepare SQLscript for dumping data for each table
    for ($i = 0; $i < $columnCount; $i++) {
        while ($row = mysqli_fetch_row($result)) {
            $sqlScript .= "INSERT INTO $table VALUES(";
            for ($j = 0; $j < $columnCount; $j++) {
                $row[$j] = $row[$j];

                if (isset($row[$j])) {
                    $sqlScript .= '"' . $row[$j] . '"';
                } else {
                    $sqlScript .= '""';
                }
                if ($j < ($columnCount - 1)) {
                    $sqlScript .= ',';
                }
            }
            $sqlScript .= ");\n";
        }
    }

    $sqlScript .= "\n";
}

if (!empty($sqlScript)) {
    // Save the SQL script to a backup file
    $path = 'db_backup/';
    $filename = $database_name . '_backup_' . time() . '.sql';
    $backup_file_name = $path . $filename;
    $fileHandler = fopen($backup_file_name, 'w+');
    $number_of_lines = fwrite($fileHandler, $sqlScript);
    fclose($fileHandler);

    uploadToDropBox($backup_file_name);

    // Simply:
   /* $date = date('Y-m-d H:i:s');
    $subject = "Database backup " . $date;
    $message = "Successfully get Database backup.";

    $status = $mail->sendMailWithAttachment("Share Digital Card", "sharedigitalcard@gmail.com", "Share Digital Card", "sharedigitalcard@gmail.com", $subject, $message, $backup_file_name);

    if ($status) {
        echo "success email";
        unlink($backup_file_name);
    } else {
        echo "failure email";
    }*/

    // Download the SQL backup file to the browser
    /*
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($backup_file_name));
    ob_clean();
    flush();
    readfile($backup_file_name);
    exec('rm ' . $backup_file_name);
    */
}

function uploadToDropBox($path)
{
    $fp = fopen($path, 'rb');
    $size = filesize($path);

    $cheaders = array('Authorization: Bearer 5pXk0epFB1AAAAAAAAAB1Gve_daeD5Xp7_ydYBEopd8K48RJXo7HXiSL44ocLTW6',
        'Content-Type: application/octet-stream',
        'Dropbox-API-Arg: {"path":"/' . $path . '", "mode":"add"}');

    $ch = curl_init('https://content.dropboxapi.com/2/files/upload');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $cheaders);
    curl_setopt($ch, CURLOPT_PUT, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_INFILE, $fp);
    curl_setopt($ch, CURLOPT_INFILESIZE, $size);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    echo $response;
    curl_close($ch);
    fclose($fp);
}
?>