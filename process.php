<?php 
function upLoadImg($image_name){
    // $image_name = $_FILES['file']['name'];
    $valid_extensions = array("jpg","jpeg","png");
    $extension = pathinfo($image_name, PATHINFO_EXTENSION);
    if(in_array($extension, $valid_extensions))
    {
        $upload_path = 'upload/' . time() . '.' . $extension;
        if(move_uploaded_file($_FILES['file']['tmp_name'], $upload_path)){
        $message = 'Image Uploaded';
        $image = $upload_path;
        }
    }
    return $upload_path;
} 
// $data = json_decode(file_get_product_detailss("php://input"));

$conn=new mysqli("localhost","root","","crud_vue");
if($conn->connect_error){
    die("Connection failed!".$conn->connect_error);
}

$result=array('error'=>false);
$action='';
if(isset($_GET['action'])){
    $action=$_GET['action'];
}
if($action=='read'){
    $_POST = json_decode(file_get_contents("php://input"),true);
    $alreadyLoadedData=$_POST['alreadyLoadedData'];
    if($alreadyLoadedData==""){
        $notIn="";
    }else{
        $alreadyLoadedData=trim($alreadyLoadedData, '"');
        $notIn="WHERE `id` NOT IN($alreadyLoadedData) ";
    }
    if(isset($_POST['displayLimit'])){
        $limit=$_POST['displayLimit'];
    }else{
        $limit=5;
    }
    // var_dump($_POST);
    $users=array();
   
    $query="SELECT * from users $notIn ORDER BY `id` DESC LIMIT  $limit";
    $sql=$conn->query($query);
    if($sql){
        while($row =$sql->fetch_assoc()) {
            array_push($users,$row);
        } 
        $result['message']="get data successfuly";
        $result['users']=$users ;
    }else{
        $result['error']=true;
        $result['message']="failed to fatches users";
    }
    // $alreadtLoaded=array_column($result['users'],'id');
   
}
if($action=='create'){
    $name=$_POST['name'];
    $email=$_POST['email'];
    $phone=$_POST['phone'];
    $image_name=$_FILES['file']['name'];
    $imgtempName=upLoadImg($image_name);
    $sql=$conn->query("INSERT INTO `users`(`name`,`email`,`phone`,`profileimg`) VALUES('$name','$email','$phone','$imgtempName')");
    if($sql){
        $result['message']="User added successfuly";
    } else {
        $result['error']=true;
        $result['message']="failed to add users";
    }
}

if($action=='update'){
    $id=$_POST['id'];
    $name=$_POST['name'];
    $email=$_POST['email'];
    $phone=$_POST['phone'];
    $sql=$conn->query("UPDATE `users` SET `name`='$name',`email`='$email',`phone`='$phone' WHERE `id`='$id'  ");
    if($sql){
        $result['message']="User update successfuly";
    } else {
        $result['error']=true;
        $result['message']="failed to update users";
    }
}
if($action=='delete'){
    $id=$_POST['id'];
    $sql=$conn->query("DELETE FROM `users` WHERE `id`='$id'");
    if($sql){
        $result['message']="User deleted successfuly";
    } else {
        $result['error']=true;
        $result['message']="failed to delete users";
    }
}
$conn->close();
echo json_encode($result);
?>