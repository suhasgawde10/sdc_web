<?php

function check_url_exits($url)
{
    /*$new_url = str_replace('https','http',$url);*/
    $headers = get_headers($url);
    return stripos($headers[0], "200 OK") ? true : false;
}

if (check_url_exits("https://www.popupbusinesscard.in/user/uploads/popupbusinesscard@gmail.com/profile/1623927675.png")) {
    echo "exist";
} else {
    echo "not exist";
}

?>
