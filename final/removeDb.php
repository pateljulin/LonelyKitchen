<?php
    include 'mainDb.php';

    $conn = setupDB();


    if(isset($_POST['search']))
    {
        $ingredients = searchUserIngredients($conn, $_POST['search']);
        $conn->close();
        echo json_encode($ingredients);
    }
    else if (isset($_POST['remove'])) {
        $result = removeIngredient($conn, $_POST['remove']);
        $conn->close();
        echo json_encode($result);
    }
    else 
    {
        $ingredients = getUserIngredients($conn);
        $conn->close();
        echo json_encode($ingredients);
    }
?>