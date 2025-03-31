<?php
function add_cookiebot_script() {
    ?>
<script src="https://cloud.ccm19.de/app.js?apiKey=68589e645f65dd8906543b3274615fdf6e0560fc6aed5ca0&amp;domain=67ea64a6c0fe9ea37e0950d2" referrerpolicy="origin"></script>   <?php
}
add_action('wp_head', 'add_cookiebot_script', 1);
