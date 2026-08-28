<?php
// Insert dbw media credit comment in <head>
function dbw_insert_credit_comment() {
    echo "\n<!--\n#####################################################\n#                                                   #\n#     made with ♥ in 2025 by dbw media              #\n#     say hello: hallo@dbw-media.de                 #\n#     or visit: https://dbw-media.de                #\n#                                                   #\n#####################################################\n-->\n";
}
add_action('wp_head', 'dbw_insert_credit_comment', 1);
