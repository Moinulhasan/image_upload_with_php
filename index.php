<?php 

error_reporting(1);
$connect_to_db = mysqli_connect('localhost', 'iotait_images', 'iotait.tech', 'iotait_image_upload');
    
    $user_id = $_POST['user_id'];
    $allowedExts = array("gif", "jpeg", "jpg", "png", "JPG", "JPEG");
    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp);


    
    if ((($_FILES["file"]["type"] == "image/gif")
    || ($_FILES["file"]["type"] == "image/jpeg")
    || ($_FILES["file"]["type"] == "image/jpg")
    || ($_FILES["file"]["type"] == "image/pjpeg")
    || ($_FILES["file"]["type"] == "image/x-png")
    || ($_FILES["file"]["type"] == "image/png"))
    && in_array($extension, $allowedExts)) {
        

      if ($_FILES["file"]["error"] > 0) {

        echo "Error: " . $_FILES["file"]["error"] . "<br>";

      } else {

        //Move the file to the uploads folder
        move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . urlencode($_FILES["file"]["name"]));

        //Get the File Location
        $filelocation = 'upload/'.urlencode($_FILES["file"]["name"]);

        //Get the File Size
        $size = ($_FILES["file"]["size"]/1024).' kB';

        //Save to your Database
        mysqli_query($connect_to_db, "INSERT INTO image_upload (user_id, filelocation, size) VALUES ('$user_id', '$filelocation', '$size')");

        //Redirect to the confirmation page, and include the file location in the URL
        // "https://iotait.tech/image_upload/".
        $arr["status_code"] = "200";
        $arr["status"] = "Image uploaded successfully.";
        $arr["image_location"] = "https://iotait.tech/bsl/".$filelocation; 
        $arr["file_title"] = $title; 
        $set["data"] = $arr; 
        print_r(json_encode($set, JSON_PRETTY_PRINT)); 
        
      }
    } else {
      //File type was invalid, so throw up a red flag!
        $arr["status_code"] = "403";
        $arr["status"] = "Error Uploading Image";
        $set["data"] = $arr; 
        print_r(json_encode($set, JSON_PRETTY_PRINT)); 
    }
