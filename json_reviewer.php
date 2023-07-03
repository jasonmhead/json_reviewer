<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Dataset</title>
</head>
<body>
    <?php

    if(isset($_REQUEST['file_name'])){
        $file_name = $_REQUEST['file_name']; // "./got_questions_dataset.json";
        $column_value = $_REQUEST['column_value'];
        $column_name = $_REQUEST['column_name'];
    }else{
        $file_name = ""; // "./got_questions_dataset.json";
        $column_value = "";
        $column_name = "";
        $contains = "";
    }

    if(isset($_REQUEST['contains'])){
        $contains = $_REQUEST['contains'];
    }else{
        $contains = 0;
    }
    
    //print_r($_REQUEST);

    function highlight_yellow($string, $pattern) {
        return preg_replace('/' . $pattern . '/i', '<span style="background-color:yellow;">' . $pattern . '</span>', $string);
    }

    function read_json_file($file_name, $column_name, $column_value, $contains) {
        $json_data = file_get_contents($file_name);
        $data = json_decode($json_data, true);



        $found_data = [];
        $returned_data = "";

        foreach ($data as $row) {

            if ($contains == 1 && strpos($row[$column_name], $column_value) !== false){ // contains
                array_push($found_data, $row);
            }

            if ($contains == 0 && $row[$column_name] == $column_value) {
                array_push($found_data, $row);
            }
        }

        if($found_data){
            $returned_data = json_encode($found_data);
            $returned_data = preg_replace('/\\\\u([0-9a-fA-F]{4})/', '&#x\\1;', $returned_data);
            $returned_data = str_replace("\\n", "<br>", $returned_data);
            $returned_data = str_replace("\",\"", "<br><hr>", $returned_data);
            $returned_data = str_replace("\":\"", "<br>", $returned_data);
            $returned_data = str_replace("\"},{\"", "<br><br><hr style='border: 1px solid blue;'><br><br>", $returned_data);
            $returned_data = highlight_yellow($returned_data, $column_value);
            return $returned_data;
        }

        return "Not found";
    }

    ?>

<form action="" method="post">
  <label>json file: </label><input type="text" name="file_name" value="<?php print($file_name); ?>"> 
  <label>column name: </label><input type="text" name="column_name" value="<?php print($column_name); ?>"> 
  <label>column value: </label><input type="text" name="column_value" value="<?php print($column_value); ?>"> 
  <input type="checkbox" name="contains" value="1" <?php if ($contains == 1) echo 'checked'; ?>> contains
  <input type="submit" value="Submit">
</form>

<div id="column_data">

<?php

if($file_name){
    $requested_data = read_json_file($file_name, $column_name, $column_value, $contains);
    echo($requested_data);
}

?>

</div>


</body>
</html>
