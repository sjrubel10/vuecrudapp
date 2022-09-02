<?php

    //upload.php
    function resize_image($file, $w, $h, $crop=FALSE) {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width-($width*abs($r-$w/$h)));
            } else {
                $height = ceil($height-($height*abs($r-$w/$h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w/$h > $r) {
                $newwidth = $h*$r;
                $newheight = $h;
            } else {
                $newheight = $w/$r;
                $newwidth = $w;
            }
        }
        $src = imagecreatefromjpeg($file);
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    
        return $dst;
    }
    // $img = resize_image(‘/path/to/some/image.jpg’, 200, 200);

    
    $image = '';

    if(isset($_FILES['file']['name']))
    {
    $image_name = $_FILES['file']['name'];
    $valid_extensions = array("jpg","jpeg","png");
    $extension = pathinfo($image_name, PATHINFO_EXTENSION);
    if(in_array($extension, $valid_extensions))
    {
    $upload_path = 'upload/' . time() . '.' . $extension;
    if(move_uploaded_file($_FILES['file']['tmp_name'], $upload_path)){
    $message = 'Image Uploaded';
    $image = $upload_path;
    }else
    {
    $message = 'There is an error while uploading image';
    }
    }
    else
    {
    $message = 'Only .jpg, .jpeg and .png Image allowed to upload';
    }
    }
    else
    {
    $message = 'Select Image';
    }

    $output = array(
    'message'  => $message,
    'image'   => $image
    );

    echo json_encode($output);


?>