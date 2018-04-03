<?php
    function setupDB() {
        $servername = "localhost";
        $username = "root";
        $password = "Giggles.com1";
        $dbname = "lonelyKitchen";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        return $conn;
    }


    function getUserIngredients($conn) {
        $sql2 = "SELECT * FROM myIngredient";
        $result = $conn->query($sql2);
        
        $toReturn = array();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $item =  array(
                    "id" => $row["id"],
                    "ingredient" => $row["ingredient"],
                    "ingredientURL" => $row["ingredientURL"],
                );
                
                array_push($toReturn, $item);
            }
        }
        
        return $toReturn;
    }
    
    function getRecipes($conn) {
        $sql = "SELECT * FROM recipe LIMIT 6";
        $result = $conn->query($sql);
        $userIngredients = getUserIngredients($conn);

        $recipes = array();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $max = intval($row["ingredientCount"]);
                $userHas = 0;
                for ($i = 1; $i <= $max; $i++) {
                    $name = $row["ingredient_" . $i];
                    
                    $name = str_replace(" of Choice", "", $name);
                    
                    foreach ($userIngredients as $ing) {
                        $test = stripos($ing["ingredient"], $name);
                        
                        //=== to test for false (and not 0)
                        if ($test === false) {
                            continue;
                        }
                        
                        $userHas++;
                        break;
                    }
                } 
                
                $percent = (floatval($userHas) / floatval($row["ingredientCount"])) * 100;
                $item =  array(
                    "percent" => $percent,
                    "name" => $row["recipe_name"],
                    "recipeURL" => $row["recipeURL"]
                );
                array_push($recipes, $item);
            }
        }
        
        return $recipes;
    }

    function getAllRecipes($conn) {
        $sql = "SELECT * FROM recipe";
        $result = $conn->query($sql);
        $userIngredients = getUserIngredients($conn);

        $recipes = array();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $max = intval($row["ingredientCount"]);
                $userHas = 0;
                for ($i = 1; $i <= $max; $i++) {
                    $name = $row["ingredient_" . $i];
                    
                    $name = str_replace(" of Choice", "", $name);
                    
                    foreach ($userIngredients as $ing) {
                        $test = stripos($ing["ingredient"], $name);
                        
                        //=== to test for false (and not 0)
                        if ($test === false) {
                            continue;
                        }
                        
                        $userHas++;
                        break;
                    }
                } 
                
                $percent = (floatval($userHas) / floatval($row["ingredientCount"])) * 100;
                $item =  array(
                    "percent" => $percent,
                    "name" => $row["recipe_name"],
                    "recipeURL" => $row["recipeURL"]
                );
                array_push($recipes, $item);
            }
        }
        
        return $recipes;
    }

    function getRecipe($conn, $name) {
        $sql = "SELECT * FROM recipe WHERE recipe_name=\"" . $name . "\"";
        $result = $conn->query($sql);
        $userIngredients = getUserIngredients($conn);

        $recipes = array();
        
        if (!$result) return $recipes;
        
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                
                $max = intval($row["ingredientCount"]);
                $userHas = 0;
                for ($i = 1; $i <= $max; $i++) {
                    $name = $row["ingredient_" . $i];
                    
                    $name = str_replace(" of Choice", "", $name);
                    
                    foreach ($userIngredients as $ing) {
                        $test = stripos($ing["ingredient"], $name);
                        
                        //=== to test for false (and not 0)
                        if ($test === false) {
                            continue;
                        }
                        
                        $userHas++;
                        break;
                    }
                } 
                
                $percent = (floatval($userHas) / floatval($row["ingredientCount"])) * 100;
                
                $item =  array(
                    "percent" => $percent,
                    "name" => $row["recipe_name"],
                    "recipeURL" => $row["recipeURL"],
                    "text" => $row["instruction"],
                    "ingredient_1" => $row["ingredient_1"],
                    "ingredient_2" => $row["ingredient_2"],
                    "ingredient_3" => $row["ingredient_3"],
                    "ingredient_4" => $row["ingredient_4"],
                    "ingredient_5" => $row["ingredient_5"],
                    "ingredient_6" => $row["ingredient_6"],
                    "ingredient_7" => $row["ingredient_7"],
                    "ingredient_8" => $row["ingredient_8"],
                    "ingredientCount" => $row["ingredientCount"],
                    "prep_time" => $row["prep_time"]
                );
                array_push($recipes, $item);
            }
        }
        
        return $recipes;
    }

    function getAllIngredients($conn) {
        $userIngredients = getUserIngredients($conn);
        
        $sql2 = "SELECT * FROM ingredient";
        $result = $conn->query($sql2);
        
        $toReturn = array();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $item =  array(
                    "id" => $row["Id"],
                    "ingredient" => $row["ingredient"],
                    "ingredientURL" => $row["ingredientURL"],
                );
                
                //Ensure we don't already have the ingredient
                $found = false;
                foreach ($userIngredients as $ing) {
                    if ($item["ingredient"] == $ing["ingredient"]) {
                        $found = true;
                        break;
                    }
                }
                
                if ($found) continue;
                
                array_push($toReturn, $item);
            }
        }
        
        return $toReturn;
    }

    function searchIngredients($conn, $search) 
    {
        $userIngredients = getUserIngredients($conn);
        $sql2 = "SELECT * FROM ingredient WHERE ingredient=\"" . $search  . "\"";
        //$sql2 = mysql_escape_string("SELECT * FROM ingredient WHERE ingredient LIKE " . $search);
        
        $result = $conn->query($sql2);
        $toReturn = array();
        
        if (!$result) return $toReturn;
        
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $item =  array(
                    "id" => $row["Id"],
                    "ingredient" => $row["ingredient"],
                    "ingredientURL" => $row["ingredientURL"],
                );
                
                //Ensure we don't already have the ingredient
                $found = false;
                foreach ($userIngredients as $ing) {
                    if ($item["ingredient"] == $ing["ingredient"]) {
                        $found = true;
                        break;
                    }
                }
                
                if ($found) continue;
                
                array_push($toReturn, $item);
            }
        }
        
        return $toReturn;
    }

    function searchUserIngredients($conn, $search) 
    {
        $sql2 = "SELECT * FROM myIngredient WHERE ingredient=\"" . $search  . "\"";
        //$sql2 = mysql_escape_string("SELECT * FROM ingredient WHERE ingredient LIKE " . $search);
        
        $result = $conn->query($sql2);
        $toReturn = array();
        
        if (!$result) return $toReturn;
        
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $item =  array(
                    "id" => $row["id"],
                    "ingredient" => $row["ingredient"],
                    "ingredientURL" => $row["ingredientURL"],
                );
                
                array_push($toReturn, $item);
            }
        }
        
        return $toReturn;
    }

    function addIngredient($conn, $value) {
        $ingredient = searchIngredients($conn, $value)[0];
        
        $sql2 = "INSERT INTO myIngredient (ingredient, ingredientURL)
VALUES (\"". $ingredient["ingredient"] . "\", \"" . $ingredient["ingredientURL"] . "\");";
        
        $result = $conn->query($sql2);
        
        return $result;
    }

    function removeIngredient($conn, $value) {
        $sql2 = "DELETE FROM myIngredient WHERE ingredient=\"". $value . "\"";
        $result = $conn->query($sql2);
        
        return $result;
    }

    function getHistory($conn) {
        $sql2 = "SELECT * FROM history";
        $result = $conn->query($sql2);
        $toReturn = array();
        
        if (!$result) return $toReturn;
        
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                
                $name = $row["recipe"];
                
                $recipe = getRecipe($conn, $name)[0];
                
                array_push($toReturn, $recipe);
            }
        }
        
        return $toReturn;
    }

    function appendHistory($conn, $recipes) {
        foreach ($recipes as $r) {
            
            $test = "SELECT * FROM history WHERE recipe=\"" . $r["name"] . "\"";
            
            $result = $conn->query($test);
            
            if (!$result) continue;
            
            if ($result->num_rows == 0) {
                $sql2 = "INSERT INTO history (recipe) VALUES (\"" . $r["name"] . "\");";

                $conn->query($sql2);
            }
        }
    }

    
?>