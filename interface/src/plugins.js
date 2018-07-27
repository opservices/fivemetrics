/* Import your third-Party plugins here */
import 'js/modernizr.js';
import 'js/perfect-scrollbar.js';
import 'js/vendor/amcharts/amcharts.js';
import 'js/vendor/amcharts/serial.js';
import 'js/vendor/amcharts/pie.js';
import 'js/vendor/amcharts/gauge.js';

(function() {
  var amcharts_bullet = AmCharts.bullet;
  AmCharts.bullet = function(container, bulletType, bulletSize, bc, ba, bbt, bbc, bba, originalSize, gradientRotation, pattern, path, dashLength) {
     	var bullet;
     	if (bulletType =='bar') {
     		bullet = AmCharts.polygon(container, [-bulletSize/15, bulletSize/15, bulletSize/15, -bulletSize/15], [bulletSize / 2, bulletSize / 2, -bulletSize / 2, -bulletSize / 2], bc, ba, bbt, bbc, bba, gradientRotation - 180, undefined, dashLength)
     	} else {
     		bullet = amcharts_bullet(container, bulletType, bulletSize, bc, ba, bbt, bbc, bba, originalSize, gradientRotation, pattern, path, dashLength)
     	}

  	if (bullet) {
          bullet.pattern(pattern, NaN, path);
      }
      return bullet;
  };
})();