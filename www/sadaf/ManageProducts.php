<?php
include "../shares/header.inc.php";
ini_set("error_reporting",E_ALL);
HTMLBegin();
$mysql = pdodb::getInstance();


if(isset($_GET["save"])){

    $query = "insert into sadaf.products (name) values (?)";
    $mysql->Prepare($query);
    $mysql->ExecuteStatement(array($_REQUEST["name"]));
}
?>

    <form method="GET">
        name:<input type="text" name="name" id="name">
        <input type="submit" name="save" value="save" id="save">
    </form>
    <div>

        <form method="POST">
            <input type="hidden" name="delete" id="delete">
            <?php

            if(isset($_POST["delete"])){
                echo "deleted";
                $query = "select * from products";
                $res = $mysql->Execute($query);
                while($rec = $res->fetch()){
                    $c_name = "ch_".$rec["ProductID"];
                    if (isset($_REQUEST[$c_name])){

                        $query = "delete from products where productID=".$rec["ProductID"];
                        $mysql->Execute($query);
                    }
                }
            }
            $mysql = pdodb::getInstance();
            $query = "select * from products";
            $products = $mysql->Execute($query);
            while($rec = $products->fetch()){
                $c_name = "ch_".$rec["ProductID"];
                echo "<input type='checkbox' name='".$c_name."' id='".$c_name."'>";
                echo "<tr>";
                echo "<td>".$rec["name"]."<td></td></br>";
                echo "</tr>";
            }
            ?>
            <input type="submit" class="btn btn-small btn-danger">
        </form>
    </div>
    </body>
</html>
