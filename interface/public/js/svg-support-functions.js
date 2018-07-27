// /* -------------------------------------------
// !!! WARNING !!!
// Snap.svg needed

// ## Don't make a mess ##
// */

// console.log("Let's talk about your messy code...");

// export default class SvgThemeSupport {

// 	getChartList(context_id) {
// 		var context = document.getElementById(context_id);
		
// 		return
// 			[].slice.call(
// 				context.querySelectorAll("svg")
// 			);
// 	}

// 	// Linechart customizations
// 	setFillGradientOnStroke(chartList) {
// 		var result = Array.isArray(chartList) ? chartList : return "It's not an array. Don't make a mess!";

// 		return
// 			result.map(function(chart){
// 				Snap(chart.querySelector("path[stroke*='linechart']"))
// 					.attr({
// 						stroke: "url(#" + chart.querySelector("linearGradient").id + ")"
// 					});
// 			});

// 	};
// }

// // Catch all svgs


// // 