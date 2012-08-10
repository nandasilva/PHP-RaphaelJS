<?php

include_once 'libs/PHPRaphael.php';

$svg = new PHPRaphael('svgmapEstado', 1066, 706, 'svg/mapa.svg');

echo '<code>' . $svg->getJs() . '</code>';