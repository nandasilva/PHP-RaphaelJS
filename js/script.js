var $ = function(el) {

	/**
	 * The element that you want to work
	 * @type {dom}
	 */
	this.el = document.getElementById(el);
	
	/**
	 * Insert a text or html tags on element
	 * @param  {content} 	content The content that you want insert 
	 * @return {this}         return the object
	 */
	this.html = function(content) {
		this.el.innerHTML = content;
		return this;
	}

	/**
	 * Add a css class in the object
	 * @param {string} class_name the name of class
	 */
	this.addClass = function(class_name) {
		this.el.className = class_name;
		return this;
	}
	
	if (this instanceof $) {
		return this.$;
	}
	else {
		return new $(el);
	}

}

// Atributos
function createRadius(op) {
	this.el = document.getElementById(op.el);

	this.radius = op.radius || 1000;

	this.bgcolor = op.bgcolor || 'red';

	this.w = op.w || 50;
	this.h = op.h || 50;

	this.el.style.width = this.w + 'px';
	this.el.style.height = this.h + 'px';
	this.el.style.backgroundColor = this.bgcolor;
	this.el.style.borderRadius = this.radius + 'px';
	this.el.style.position = 'fixed';
	this.el.style.top = '50%';
	this.el.style.left = '50%';

	return this.el;
}

function animateRadius(radius) {
	var el = radius;

	var start = 0;

	var animate = setInterval(function() {

		el.style.top = 50 * Math.sin( start ) + 80 + 'px';

		start += 0.05;


	}, 1000 / 30); 

}


var radius = new createRadius({
	el: 'radius'
});

var animate = new animateRadius(radius);

console.log(radius)


