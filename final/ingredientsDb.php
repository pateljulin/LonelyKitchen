<?php
    include 'mainDb.php';

    $conn = setupDB();


    if(isset($_POST['search']))
    {
        $ingredients = searchIngredients($conn, $_POST['search']);
        $conn->close();
        echo json_encode($ingredients);
    }
    else if (isset($_POST['add'])) {
        $result = addIngredient($conn, $_POST['add']);
        $conn->close();
        echo json_encode($result);
    }
    else 
    {
        $ingredients = getAllIngredients($conn);
        $conn->close();
        echo json_encode($ingredients);
    }
?>