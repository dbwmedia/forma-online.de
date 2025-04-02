<?php
// Die Datei, die Sie in Ihr Child-Theme-Verzeichnis einfügen können

function buffer_start() {
    ob_start("insert_custom_comment");
}

function buffer_end() {
    // Prüft, ob ein Output Buffer aktiv ist, bevor versucht wird, ihn zu beenden
    if (ob_get_level() > 0) {
        ob_end_flush();
    }
}

function insert_custom_comment($buffer) {
    $custom_html_comment = <<<EOD

<!--
##########################################################################
#                                                                        # 
#          ===..===.                                                     # 
#         .@@@:.@@@:                                                     # 
#     -*%*=@@@:.@@@-*##+. .+++  .++=  -+++                               # 
#   .@@@@@@@@@:.@@@@@@@@@= %@@* %@@@  %@@.                               # 
#   #@@=  .@@@:.@@@:  .@@@ .@@@.@@@@@:@@#  ,@@@  @@@   @@@,              # 
#   %@@-  .@@@:.@@@.  .@@@  *@@#@@=@@@@@.  @@@@  @@@@  @@@@              # 
#   :@@@*:@@@@:.@@@@=-@@@#  .@@@@=.@@@@+   '@@@  @@@   @@@'              #     
#     *@@@#%@@..%@@*@@@%-    =@@@. -@@@.                                 # 
#                                                 #@@-                   # 
#                                                 #@@-                   # 
#              .@@@@@@@@@@@@@@=  :@@@@@@%.  +@@@@@@@@- *@@- .@@@@@@@@.   # 
#              .@@@+ .@@@: :@@% .@@%  .@@@.+@@@. .@@@- *@@- =%%-  %@@-   # 
#              .@@@:  @@@  .@@% #@@@@@@@@@.*@@=   +@@- *@@- :@@@@@@@@-   # 
#              .@@@:  @@@  .@@% .@@%  .#@%.-@@@. :@@@- *@@- @@@. .%@@-   # 
#              .@@@:  @@@  .@@%  :@@@@@@@.  -@@@@@@@@- *@@- +@@@@@@@@=   # 
#                                                                        #
#              made with ♥ in 2024 by dbw media                          #
#                                                                        #
##########################################################################

if you want to say hello, do so at: hallo@dbw-media.de or visit dbw-media.de

-->
EOD;

    // Sucht den Anfang des <head>-Tags und fügt den Kommentar direkt davor ein
    $buffer = preg_replace("/(<head.*>)/", $custom_html_comment.'$1', $buffer, 1);

    return $buffer;
}

add_action('after_setup_theme', 'buffer_start');
add_action('shutdown', 'buffer_end');
?>
