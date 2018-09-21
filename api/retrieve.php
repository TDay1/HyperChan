<?php

include './config.php';

/*
$servername = "127.0.0.1";
$username = "root";
$password = passwordcrypt( 'YXV1RjBEOHFuQnpRQjVnQnR0TXZvZz09 ', 'd' );
$dbname = "socialmedia";
*/


    //open connection to mysql db
    $connection = mysqli_connect($servername,$username,$password,$dbname) or die("Error " . mysqli_error($connection));

if(isset($_GET["page"])) {
  if(ctype_digit($_GET["page"])) {
  $page = 10 * ($_GET["page"]);
  //fetch table rows from mysql db
  $sql = "SELECT postid, post, dateid, imageenabled, imageext FROM posts ORDER BY postid DESC LIMIT 10 OFFSET $page;";
  $result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));

}
else{
  echo "<h1>error: bad input</h1>";
  $page = "";
}
  }
  elseif(isset($_GET["thread"])){
    if(ctype_digit($_GET["thread"])) {
    $thread = ($_GET["thread"]);
    //fetch table rows from mysql db
    $sql = "SELECT commentid, postid, comment, dateid, imageenabled, imageext FROM comments WHERE postid = $thread ORDER BY commentid ASC LIMIT 250;";
    $result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));
  }
}
  elseif(isset($_GET["post"])){
    if(ctype_digit($_GET["post"])) {
    $post = ($_GET["post"]);
    //fetch table rows from mysql db
    $sql = "SELECT postid, post, dateid, imageenabled, imageext FROM posts WHERE postid = $post Order By postid ASC LIMIT 1;";
    $result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));
  }
}
  else {
    echo "<h1>error: bad input</h1>";
    $thread = "";

  }

    //create an array
    $emparray = array();
    while($row =mysqli_fetch_assoc($result))
    {
        $emparray[] = $row;
    }
    $result_encoded = json_encode($emparray);


    if($result_encoded == "[]") {
      echo '[{"postid":"999","post":"There does not appear to be any posts here. If you want to start one just click "create a thread" above.","dateid":"","imageenabled":0,"imageext":null}]';
      //echo "";
    }
    else {
      echo $result_encoded;
    }

    //close the db connection
    mysqli_close($connection);

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
