<?php $arr_album = glob("https://bahanu.com/assets/files/thumb/*");
foreach ($arr_album as $img) {
    $name = str_replace('https://bahanu.com/assets/files/thumb/', '', $img);
    // $new_name = str_replace('png', 'jpg', $name);
    // $num= str_replace('.jpg', '', $new_name_2);
    // if($i > '1000'){
    // echo $new_name."<br>";
    resize_image('https://bahanu.com/' . $img, $name, 700, 700, 100, $type = "", $new_path = "https://bahanu.com/assets/files/thumbs/");

    // }
}
