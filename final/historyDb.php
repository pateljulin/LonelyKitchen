<?php
    include 'mainDb.php';

    $conn = setupDB();

    $recipes = getHistory($conn);
    $conn->close();
    echo json_encode($recipes);

?>