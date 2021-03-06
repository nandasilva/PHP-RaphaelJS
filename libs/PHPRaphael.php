<?php

/**
 * MIT License
 * ===========
 *
 * Copyright (c) 2012 Cezar Luiz <cezarluiz.c@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @package     cezarluiz.c@gmail.com
 * @author      Cezar Luiz <cezarluiz.c@gmail.com>
 * @copyright   2012 Cezar Luiz.
 * @license     http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link        http://www.cezarluiz.com.br
 * @version     1.0
 */

class PHPRaphael {
	private $svg;		
	private $width;
	private $height;
	private $element;
	private $js;
	private $attr;
	private $arr;

	/**
	 * Create the javascript element
	 * @param string $element 	Name of element
	 * @param int $width   		Width of element
	 * @param int $height  		Height of element
	 * @param string $svg     	The name of file
	 */
	public function __construct($element, $width, $height, $svg = null) {
		$this->element = $element;
		$this->width = $width;
		$this->height = $height;

		if(!is_null($svg)) {
			$this->svg = $this->readSvg($svg);
		}

		$this->js .= "var $element = new Raphael('$element', $width, $height);";

		$this->createElementsSvg();
		$this->createAttrSvg();
		$this->createIdSvg();
		$this->createArrayElements();
		$this->createHoverEffects();

		return $this;
	}

	/**
	 * Read SVG file
	 * @param  string $svg 	Name of file
	 * @return object      	Return a object of SVGFile
	 */
	private function readSvg($svg) {
		return $this->svg = simplexml_load_file($svg);
	}

	/**
	 * Create the path of SVG like 'd=""' tag
	 * @return void 
	 */
	private function createElementsSvg() {
		$i = 1;

		foreach($this->svg as $s) {
			// Break the loop if find gradient
			if(isset($s['gradientUnits']))
				break;

			// Get the name of tag
			$type = $s->getName();

			// Create de javascript variable name
			$varName = isset($s['id']) ? $s['id'] : $type . $i;
				
			// var svgObj = element.path('dTag');
			$this->js .= "var {$varName} = {$this->element}.{$type}();";

			// Increment $i
			$i++;
		}
	}

	/**
	 * Create the attrs of SVG file, like fill, stroke-width and more
	 * @return void 
	 */
	private function createAttrSvg() {
		$i = 1;
		foreach($this->svg as $s) {
			// Break the loop if find gradient
			if(isset($s['gradientUnits']))
				break;


			$attr = '';

			// foreach in attributes
			foreach($s->attributes() as $k => $a) {
				// Rename the attr 'd' to 'path'
				// * based on Raphael JS Lib *
				if($k == 'd') 
					$k = 'path';

				// Make the attrs
				$attr .= "\"{$k}\": \"{$a}\",";
			}

			/**
			 * Defaults attr
			 */
			$attr .= "\"stroke-width\": 0,";

			// Get the name of tag
			$type = $s->getName();

			// Remove last comma
			$attr = substr($attr, 0, -1);

			// Create de javascript variable name
			$varName = isset($s['id']) ? $s['id'] : $type . $i;

			// Append in js result the attrs
			$this->js .= "{$varName}.attr({ {$attr} });";


			// Increment $i
			$i++;
		}
	}

	/**
	 * Create a array with all elements and variables
	 * You can use this array for create effects, etc
	 * @return void 
	 */
	private function createArrayElements() {
		$i = 1;

		foreach($this->svg as $a) {
			// Break the loop if find gradient
			if(isset($a['gradientUnits']))
				break;

			// Get the name of tag
			$type = $a->getName();

			// Create de javascript variable name
			$varName = isset($s['id']) ? $s['id'] : $type . $i;

			// Create names
			$arr .= "{$varName},";

			$i++;
		}

		// Remove last comma
		$arr = substr($arr, 0, -1);

		$this->js .= "var objs_{$this->element} = [{$arr}];";

	}

	/**
	 * Create the hover effects in svg elements
	 * @param  string  $from The initial color
	 * @param  string  $to   The final color
	 * @param  integer $time Time of animation in miliseconds
	 * @return void        
	 */
	public function createHoverEffects($from = '#FFF', $to = '#CCC', $time = 200) {
		$this->js .= "for(var svgObj in objs_{$this->element}) {";

			$this->js .= "var obj = objs_{$this->element}[svgObj];";
			$this->js .= "obj.hover(function(){
								this.animate({
									fill: '{$from}'
								}, {$time});
							}, function(){
								this.animate({
									fill: '{$to}'
								}, {$time});
							});";

		$this->js .= "}";
	}

	/**
	 * Create the SVG id like 'id=""'
	 * It will create the ID only if the path, circle, etc contains the ID tag
	 * @return void
	 */
	private function createIdSvg() {
		foreach($this->svg as $s) {
			// If exist gradient, break the loop
			if(isset($s['gradientUnits']))
				break;

			// If exist id create the id
			if(isset($s['id']))
				// svgObj.node.id = 'val';
				$this->js .= "{$s['id']}.node.id = '{$s['id']}';";
		}
	}

	/**
	 * Get the JavaScript code
	 * @return string
	 */
	public function getJs() {
		return $this->js;
	}

}