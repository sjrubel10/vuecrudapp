<?php
$userdb = array(
    array(
        'uid' => '100',
        'name' => 'Sandra Shush',
        'pic_square' => 'urlof100'
    ),
    array(
        'uid' => '5465',
        'name' => 'Stefanie Mcmohn',
        'pic_square' => 'urlof100'
    ),
    array(
        'uid' => '40489',
        'name' => 'Michael',
        'pic_square' => 'urlof40489'
    )
);


function searchForId($id, $array) {
   foreach ($array as $key => $val) {
       if ($val['uid'] === $id) {
           return $key;
       }
   }
   return null;
}
$id=40489;
$print_r=(array_column($userdb, 'uid'));
$key = array_search('100', $print_r);
// unset($userdb[$key]);
print_r($print_r);

?>

<script>
    console.log("Ok");
    selected_products[0]=["r1","7up",61,"Albertsons"];
    selected_products[1]=["r3", "Arrowhead",78,"Arrowhead "];
    selected_products[2]=["r8", "Betty Crocker Cake Mix (Variety)",109,"Arrowhead "];
    alert(selected_products.length);
    var result;
    for( var i = 0, len = selected_products.length; i < len; i++ ) {
        if( selected_products[i][0] === 'r1' ) {
            result = selected_products[i];
            break;
        }
    }
    console.log(result);
</script>