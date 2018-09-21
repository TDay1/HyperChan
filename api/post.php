<!DOCTYPE HTML>
<html>
<head>


  </head>
  <body>
    <center>

<?php
include './config.php';

$dateid = time();


$comment = $post = "";
$uploaddir = '../img/posts/';
$uploadname = basename($_FILES['file']['name']);
$imageext = pathinfo($uploadname, PATHINFO_EXTENSION);


        if($displayerrors == true) {
          ini_set('display_errors', 1);
          ini_set('display_startup_errors', 1);
          error_reporting(E_ALL);
        }
        else {
          ini_set('display_errors', 0);
          ini_set('display_startup_errors', 0);
          error_reporting(0);
        }

         // define variables and set to empty values
         if ($_SERVER["REQUEST_METHOD"] == "POST") {

           if( isset($_POST["post"])){
            $post = test_input_post($_POST["post"]);

            echo '<meta http-equiv="refresh" content="1.5;url=/" />';
            echo '</head><body>center>';
          }

          elseif( isset($_POST["comment"])){
           $post = test_input_comment($_POST["comment"]);
         }
         else{
          echo "error";
         }

         }

         function test_input_post($data) {
            $data = str_replace('<br />', PHP_EOL, $data);
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            //check for images
            if ($_FILES['file']['name'] != ""){
              $imageenabled = 1;
            }
            else{
              $imageenabled = 0;
            }
            submit_post($data, $imageenabled);

         }

         function test_input_comment($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            //check for images
            if ($_FILES['file']['name'] != ""){
              $imageenabled = 1;
            }
            else{
              $imageenabled = 0;
            }
            $commentpostid = ($_POST['commentpostid']);
            echo '<meta http-equiv="refresh" content="1.5;url=/thread.html?thread=' . $commentpostid . '" />';
            echo '</head><body><center>';
            submit_comment($data, $imageenabled, $commentpostid);

         }


      function submit_post($post, $imageenabled) {
        global $dateid, $imageext, $servername, $username, $password, $dbname;

        /*
         // Create connection
         $conn = new mysqli($servername, $username, $password, $dbname);
         // Check connection
         if ($conn->connect_error) {
            die("<h1>Configure your database properly, idiot.</h1> <br> <p>Connection failed: " . $conn->connect_error . "</p>");
         }

         $sql = "INSERT INTO posts (post,dateid,imageenabled,imageext)
         VALUES ('$post','$date', $imageenabled, '$imageext');";*/


         /* Attempt MySQL server connection. Assuming you are running MySQL
         server with default setting (user 'root' with no password) */

         //added need to fix
         $link = mysqli_connect($servername, $username, $password, $dbname);

         // Check connection
         if($link === false){
           echo '<meta http-equiv="refresh" content="1.5;url=/" />';
           echo '</head><body><center>';
           die("ERROR: Could not connect. " . mysqli_connect_error());
         }
         // Prepare an insert statement
         $sql = "INSERT INTO posts (post,dateid,imageenabled,imageext) VALUES (?, ?, ?, ?)";
         if($stmt = mysqli_prepare($link, $sql)){
           // Bind variables to the prepared statement as parameters
           mysqli_stmt_bind_param($stmt, "ssis", $post, $dateid, $imageenabled, $imageext);
           mysqli_stmt_execute($stmt);
           /* Set the parameters values and execute
           the statement to insert a row */
           $mysqlinsertboolpost = true;
         } else{
           echo "ERROR: Could not prepare query: $sql. " . mysqli_error($link);
         }
         // Close statement
         mysqli_stmt_close($stmt);
         // Close connection
         // -added



         if ($mysqlinsertboolpost === TRUE) {

           $last_id = mysqli_insert_id($link);

            if($_FILES['file']['name'] != "") { //if image is also being uploaded

            if(uploadimage('file', true, false, $last_id, 'post')) {
              echo "<center><h1>image post successful<h1><br></center>";

            }
            else {
              echo "<center><h1>Post Failed</h1></center>";
            }

          }

            else{ // text post successful
              echo "<h1> Post successful! </h1><br>";
              echo "<p> Your post ID is: " .$last_id . "</p><br> <br>";
            }

         }

          //Both image and text fail
          else {
            echo "<h1> Error: </h1>";
            echo "<p>" . "Error: " . $stmt . "<br>" . $conn->error . "</p>";
            echo "<br>";
         }

         mysqli_close($link);
       }

// commenter

function submit_comment($comment, $imageenabled, $commentpostid) {
  global $dateid, $imageext, $servername, $username, $password, $dbname;


  /*
   // Create connection
   $conn = new mysqli($servername, $username, $password, $dbname);
   // Check connection
   if ($conn->connect_error) {
      die("<h1>Configure your database properly, idiot.</h1> <br> <p>Connection failed: " . $conn->connect_error . "</p>");
   }

   $sql = "INSERT INTO comments (postid,comment,dateid, imageenabled, imageext)
   VALUES ('$commentpostid','$comment','$date', $imageenabled, '$imageext');";*/

   $link = mysqli_connect($servername, $username, $password, $dbname);

   // Check connection
   if($link === false){
     echo '<meta http-equiv="refresh" content="1.5;url=/" />';
     echo '</head><body><center>';
     die("ERROR: Could not connect. " . mysqli_connect_error());
   }
   // Prepare an insert statement
   $sql = "INSERT INTO comments (postid,comment,dateid, imageenabled, imageext) VALUES (?, ?, ?, ?, ?)";
   if($stmt = mysqli_prepare($link, $sql)){
     // Bind variables to the prepared statement as parameters
     mysqli_stmt_bind_param($stmt, "issis", $commentpostid, $comment, $dateid, $imageenabled, $imageext);
     mysqli_stmt_execute($stmt);
     /* Set the parameters values and execute
     the statement to insert a row */
     $mysqlinsertboolcomment = true;
   } else{
     echo '<meta http-equiv="refresh" content="1.5;url=/" />';
     echo '</head><body><center>';
     echo "ERROR: Could not prepare query: $sql. " . mysqli_error($link);
   }
   // Close statement
   mysqli_stmt_close($stmt);
   // Close connection
   // -added

   if ($mysqlinsertboolcomment === TRUE) {

     $last_id = mysqli_insert_id($link);

      if($_FILES['file']['name'] != "") { //if image is also being uploaded

      if(uploadimage('file', true, false, $last_id, 'comment')) {
        echo "<center><h1>image post successful<h1><br></center>";

      }
      else {
        echo "<center><h1>Post Failed</h1></center>";
      }

    }

      else{ // text post successful
        echo "<h1> Post successful! </h1><br>";
        echo "<p> Your post ID is: " .$last_id . "</p><br> <br>";
      }

   }

    //Both image and text fail
    else {
      echo "<h1> Error: </h1>";
      echo "<p>" . "Error: " . $sql . "<br>" . $conn->error . "</p>";
      echo "<br>";
   }

   mysqli_close($link);
 }

//added

          function uploadimage($file_field, $check_image, $random_name, $name, $type) {

          //Config Section
          //Set file upload path
          if($type == 'post'){
          $path = '../img/posts/'; //with trailing slash
          }
          elseif($type == 'comment'){
          $path = '../img/comments/'; //with trailing slash
          }
          else{
            echo "error";
          }
          //Set max file size in bytes
          $max_size = 1000000;
          //Set default file extension whitelist
          $whitelist_ext = array('jpeg','jpg','png','gif');
          //Set default file type whitelist
          $whitelist_type = array('image/jpeg', 'image/jpg', 'image/png','image/gif');

          //The Validation
          // Create an array to hold any output
          $out = array('error'=>null);

          if (!$file_field) {
            $out['error'][] = "Please specify a valid form field name";
          }

          if (!$path) {
            $out['error'][] = "Please specify a valid upload path";
          }

          if (count($out['error'])>0) {
            return $out;
          }

          //Make sure that there is a file
          if((!empty($_FILES[$file_field])) && ($_FILES[$file_field]['error'] == 0)) {

          // Get filename
          $file_info = pathinfo($_FILES[$file_field]['name']);
          //$name = $file_info['filename'];
          $ext = $file_info['extension'];

          //Check file has the right extension
          if (!in_array($ext, $whitelist_ext)) {
            $out['error'][] = "Invalid file Extension";
          }

          //Check that the file is of the right type
          if (!in_array($_FILES[$file_field]["type"], $whitelist_type)) {
            $out['error'][] = "Invalid file Type";
          }

          //Check that the file is not too big
          if ($_FILES[$file_field]["size"] > $max_size) {
            $out['error'][] = "File is too big";
          }

          //If $check image is set as true
          if ($check_image) {
            if (!getimagesize($_FILES[$file_field]['tmp_name'])) {
              $out['error'][] = "Uploaded file is not a valid image";
            }
          }

          //Create full filename including path
          if ($random_name) {
            // Generate random filename
            $tmp = str_replace(array('.',' '), array('',''), microtime());

            if (!$tmp || $tmp == '') {
              $out['error'][] = "File must have a name";
            }
            $newname = $tmp.'.'.$ext;
          } else {
              $newname = $name.'.'.$ext;
          }

          //Check if file already exists on server
          if (file_exists($path.$newname)) {
            $out['error'][] = "A file with this name already exists";
          }

          if (count($out['error'])>0) {
            //The file has not correctly validated
            return $out;
          }

          if (move_uploaded_file($_FILES[$file_field]['tmp_name'], $path.$newname)) {
            //Success
            $out['filepath'] = $path;
            $out['filename'] = $newname;
            return $out;
          } else {
            $out['error'][] = "Server Error!";
          }

           } else {
            $out['error'][] = "No file uploaded";
            return $out;
           }
          }

/*
          if (isset($_POST['submit'])) {
           $file = uploadimage('file', true, true, $name);
           if (is_array($file['error'])) {
            $message = '';
            foreach ($file['error'] as $msg) {
            $message .= '<p>'.$msg.'</p>';
           }
          } else {
           $message = "File uploaded successfully".$newname;
          }
           echo $message;
          }
        */

// !added

/*
       //images
       //probably going to use open source secure image uploader library later on.
function uploadimage($name){
       global $uploaddir, $uploadname, $imageext;


       $uploadfile = $uploaddir . $name . "." . $imageext;

       if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
           echo "Image succesfully uploaded.";
           return true;
       } else {
           echo "Image uploading failed.";
           return false;
       }
}

*/
//password decrypt

         function passwordcrypt( $string, $action = 'e' ) {
             // you may change these values to your own
             $secret_key = '1755689';
             $secret_iv = '1880329';

             $output = false;
             $encrypt_method = "AES-256-CBC";
             $key = hash( 'sha256', $secret_key );
             $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

             if( $action == 'e' ) {
                 $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
             }
             else if( $action == 'd' ){
                 $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
             }

             return $output;
         }

?>
</center>
</body>
</html>
