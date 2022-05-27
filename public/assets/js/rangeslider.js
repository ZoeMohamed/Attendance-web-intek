$(function() {
	'use strict'
	
	$('.rangeslider1').ionRangeSlider();
	$('.rangeslider2').ionRangeSlider({
		min: 100,
		max: 1000,
		from: 550
	});
	
	$('.rangeslider4').ionRangeSlider({
		type: 'double',
		grid: true,
		min: -1000,
		max: 1000,
		from: -500,
		to: 500,
		step: 250
	});
});