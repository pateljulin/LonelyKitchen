<?php
    include 'mainDb.php';

    $conn = setupDB();

    if(isset($_POST['name']))
    {
        $recipe = getRecipe($conn, $_POST['name']);
        appendHistory($conn, $recipe);
        $conn->close();
        echo json_encode($recipe);
    } else {
        $recipes = getRecipes($conn);
        $conn->close();
        echo json_encode($recipes);
    }

?>