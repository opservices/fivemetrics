export default class Color {

    interpolate(start, end, steps, count) {
        var s = start,
            e = end,
            final = s + (((e - s) / steps) * count);
        return Math.floor(final);
    }

    color(_r, _g, _b) {
        var r, g, b;
        var setColors = function(_r, _g, _b) {
            r = _r;
            g = _g;
            b = _b;
        };

        setColors(_r, _g, _b);
        this.getColors = function() {
            var colors = {
                r: r,
                g: g,
                b: b
            };
            return colors;
        };
    }


    Range(value,cstart,cend) {
        let val = parseInt(value/2),
        c1 = new this.color(...cstart),
        c2 = new this.color(...cend),
        start = c1,
        end = c2;

        /*if (val > 50) {
            start = c1,
                end = c2;
            val = val % 51;
        }*/

        var startColors = start.getColors(),
            endColors = end.getColors();
        var r = this.interpolate(startColors.r, endColors.r, 50, val);
        var g = this.interpolate(startColors.g, endColors.g, 50, val);
        var b = this.interpolate(startColors.b, endColors.b, 50, val);

        return {
            toHex: () => {
                return this.rgbToHex(r,g,b)
            }
        }
    }

    rgbToHex(R,G,B) {
        return this.toHex(R)+this.toHex(G)+this.toHex(B)
    }

    hexToRgb(h) {
        return [parseInt(h.slice(1,3), 16),parseInt(h.slice(3,5), 16),parseInt(h.slice(5,7), 16)]
    }

    toHex(n) {
         n = parseInt(n,10);
         if (isNaN(n)) return "00";
         n = Math.max(0,Math.min(n,255));
         return "0123456789ABCDEF".charAt((n-n%16)/16)
              + "0123456789ABCDEF".charAt(n%16);
    }
}