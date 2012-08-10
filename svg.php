<?php
/**
 * Include de PHP class file
 */
include_once 'libs/PHPRaphael.php';


/**
 * Instace of the class PHPRaphael, passing four parameters
 * @param string 	The name of your html tag that you want append the svg
 * @param int 		Width of your element
 * @param int 		Height of your element
 * @param string 	The path where your svg file is
 * @var PHPRaphael
 */
$svg = new PHPRaphael('svgmapEstado', 1066, 706, 'svg/mapa.svg');


/**
 * Show the code...
 */
echo '<code>' . $svg->getJs() . '</code>';