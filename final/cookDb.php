<?php
    include 'mainDb.php';

    $conn = setupDB();

    $recipes = getAllRecipes($conn);
    $conn->close();
    echo json_encode($recipes);

?>