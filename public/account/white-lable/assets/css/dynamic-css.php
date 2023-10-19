<?php
include_once '../../controller/theme-config.php';
header("Content-type: text/css");

$theme = THEME_COLORS;
$icon = ICON_COLORS;
$header = HEADER_COLORS;

//echo $header;
//$footer = FOOTER_COLOR;
?>
:root {
--bg-color: <?= $theme ?>;
--icon-color: <?= $icon ?>;
--header-color: <?= $header ?>;
<!----footer-color: --><?//= $footer ?><!--;-->
}