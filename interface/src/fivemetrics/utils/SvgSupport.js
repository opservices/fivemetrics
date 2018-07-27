/* -------------------------------------------
!!! WARNING !!!
Snap.svg needed

## Don't make a mess ##
*/

export default class SvgSupport {

	// Catch all svgs
	static getChart(context) {
		if(typeof window !== 'undefined') {
			const svg = context.querySelector("svg");
			return svg;

		}
	}

	// Linechart customizations
	static setFillGradientOnStroke(chart) {
		const chart_gradient_id = chart.querySelector("linearGradient").id;
		const result = Snap(chart.querySelector("path[stroke*='linechart']"))
			.attr("stroke","url(#" + chart_gradient_id + ")");
		
	}
}