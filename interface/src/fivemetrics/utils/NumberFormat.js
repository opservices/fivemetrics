
const unitBytes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB']

const unitNumbers = ['K', 'M', 'B', 'T']

const currencyNames = {'$' : 'USD', '£': 'Pound', 'R$': 'Real', '€': 'Euro'}

class NumberFormat {

    static round(number, precision) {
        let factor = Math.pow(10, precision);
        let tempNumber = number * factor;
        let roundedTempNumber = Math.round(tempNumber);
        return roundedTempNumber / factor;
    }

    static formatCurrency(value, c=2, d='.', t=',') {
        var n = value,
        c = isNaN(c = Math.abs(c)) ? 2 : c,
        d = d == undefined ? "." : d,
        t = t == undefined ? "," : t,
        s = n < 0 ? "-" : "",
        i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
        j = (j = i.length) > 3 ? j % 3 : 0;
       return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    }

    static format(orivalue, oriunit = '', style = {}) {

        let value, unit, unit_position, unit_name, number_format, formatted, precision, format = ''

        number_format = style.number_format || ''

        if (this.isNumeric(orivalue)) {
             switch(number_format) {
              case 'currency':
                precision = style.precision || 2
                value = this.formatCurrency(orivalue, precision)//Number(orivalue).toFixed(2)
                unit = oriunit || style.unit || '$'
                unit_position = 'left'
                unit_name = (this.currencyNames[unit]) ? this.currencyNames[unit] : ''
                formatted = unit+value
              break;

              case 'bytes':
                format = this.formatBytes(orivalue,true)
                value = format.value
                unit = oriunit || style.unit || format.unit || ''
                unit_position = 'right'
                unit_name = unit+'Bytes'
                formatted = value+unit
              break;

              case 'metric':
                format = this.formatBytes(orivalue,false)
                value = format.value
                unit = oriunit || style.unit || format.unit || ''
                unit_position = 'right'
                unit_name = unit
                formatted = value+unit
              break;

              default:
                value = orivalue
                unit = oriunit || style.unit || ''
                unit_position = style.unit_position || 'right'
                unit_name = style.unit_name || ''
                formatted = (unit_position == 'right') ? value + ' ' + (unit || unit_name) : unit+value
              break
            }
        } else {
            value = orivalue
            unit = ''
            unit_position = 'right'
            unit_name = ''
            formatted = value
        }

        return {
            toString: () => {
                return formatted
            },
            value: value,
            orivalue: orivalue,
            unit: unit,
            unit_position: unit_position,
            unit_name: unit_name,
            number_format: number_format
        }
    }

    static isNumeric(value) {
        return !isNaN(parseFloat(value))
    }

    static formatBytes(bytes, binaryPrefix) {
        if (binaryPrefix) {
            if (bytes == 0) return {"value": 0, "unit" : 'B'}
            let i = Math.floor(Math.log(bytes) / Math.log(1024))
            let value = this.round(bytes / Math.pow(1024, i), 1)
            let unit = (this.unitBytes[i]) ? this.unitBytes[i] : 'B'
            return {"value": value, "unit": unit};
        }
        let i = Math.floor(Math.log(bytes) / Math.log(1000))
        let value = this.round(bytes / Math.pow(1000, i), 3)
        let unit = (this.unitNumbers[i -1]) ? this.unitNumbers[i -1] : 'K'
        return {"value": value, "unit": unit}
    }
}

NumberFormat.unitBytes = unitBytes
NumberFormat.unitNumbers = unitNumbers
NumberFormat.currencyNames = currencyNames


export default NumberFormat