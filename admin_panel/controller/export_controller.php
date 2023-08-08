<?php
require "../../dao/connection.php";

if (isset($_POST['export-user-data'])) {
    echo "exporting";

    $query = "SELECT * FROM users";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get the file path from the form input
    $csvFilePath = $_POST['file-path'];

    // Open the CSV file for writing
    $csvFile = fopen($csvFilePath, 'w');

    fputcsv($csvFile, array_keys($data[0]));
    if ($csvFile === false) {
        die("Failed to open CSV file for writing.");
    }

    foreach ($data as $row) {
        fputcsv($csvFile, $row);
    }
    fclose($csvFile);

    echo '<script>
    alert("CSV file has been successfully exported.");
    location.href = "../setting.php";
    </script>';
    
}
// Close the CSV file
