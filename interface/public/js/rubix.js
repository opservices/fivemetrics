window.Rubix = window.Rubix || {};
/*
 * debouncedresize: special jQuery event that happens once after a window resize
 *
 * latest version and complete README available on Github:
 * https://github.com/louisremi/jquery-smartresize
 *
 * Copyright 2012 @louis_remi
 * Licensed under the MIT license.
 *
 * This saved you an hour of work?
 * Send me music http://www.amazon.co.uk/wishlist/HNTU0468LQON
 */
(function($) {

var $event = $.event,
    $special,
    resizeTimeout;

$special = $event.special.debouncedresize = {
    setup: function() {
        $( this ).on( "resize", $special.handler );
    },
    teardown: function() {
        $( this ).off( "resize", $special.handler );
    },
    handler: function( event, execAsap ) {
        // Save the context
        var context = this,
            args = arguments,
            dispatch = function() {
                // set correct event type
                event.type = "debouncedresize";
                $event.dispatch.apply( context, args );
            };

        if ( resizeTimeout ) {
            clearTimeout( resizeTimeout );
        }

        execAsap ?
            dispatch() :
            resizeTimeout = setTimeout( dispatch, $special.threshold );
    },
    threshold: 150
};

})(jQuery);

/*
 * throttledresize: special jQuery event that happens at a reduced rate compared to "resize"
 *
 * latest version and complete README available on Github:
 * https://github.com/louisremi/jquery-smartresize
 *
 * Copyright 2012 @louis_remi
 * Licensed under the MIT license.
 *
 * This saved you an hour of work?
 * Send me music http://www.amazon.co.uk/wishlist/HNTU0468LQON
 */
(function($) {

var $event = $.event,
    $special,
    dummy = {_:0},
    frame = 0,
    wasResized, animRunning;

$special = $event.special.throttledresize = {
    setup: function() {
        $( this ).on( "resize", $special.handler );
    },
    teardown: function() {
        $( this ).off( "resize", $special.handler );
    },
    handler: function( event, execAsap ) {
        // Save the context
        var context = this,
            args = arguments;

        wasResized = true;

        if ( !animRunning ) {
            setInterval(function(){
                frame++;

                if ( frame > $special.threshold && wasResized || execAsap ) {
                    // set correct event type
                    event.type = "throttledresize";
                    $event.dispatch.apply( context, args );
                    wasResized = false;
                    frame = 0;
                }
                if ( frame > 9 ) {
                    $(dummy).stop();
                    animRunning = false;
                    frame = 0;
                }
            }, 30);
            animRunning = true;
        }
    },
    threshold: 0
};

})(jQuery);

$.fn.serializeObject=function(){"use strict";var a={},b=function(b,c){var d=a[c.name];"undefined"!=typeof d&&d!==null?$.isArray(d)?d.push(c.value):a[c.name]=[d,c.value]:a[c.name]=c.value};return $.each(this.serializeArray(),b),a};


var RubixListeners = [];

var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
var useTable = isSafari ? 'inline' : 'table';

/**
 * @param {string} id
 * @param {?Object} opts
 * @constructor
 */
Rubix = function(id, opts) {
    this.chart_counter = 0;
    this.master_id = this.uid('master_id');
    RubixListeners.push(this.master_id);

    this.data_stack = {};
    this.chart_stack = {};

    this.area_stack_data_stack = [];
    this.column_stack_data_stack = [];

    this.root_elem = $(id);
    this.root_elem.css('position', 'relative');
    this.root_elem.addClass('rubixcc-main-chart');
    this.root_elem.append('<div class="rubixcc-tooltip"></div>');
    this.root_elem.append('<div class="rubixcc-title"></div>');
    this.root_elem.append('<div class="rubixcc-subtitle"></div>');
    this.root_elem.append('<div class="rubixcc-chart text-center"><div style="margin-top:5px">Loading...</div></div>');
    this.root_elem.append('<div class="rubixcc-legend"></div>');

    var width = opts.width || '100%';
    var height = opts.height || 150;
    this.root_elem.width(width).height(height);

    this.elem = this.root_elem.find('.rubixcc-chart');

    opts.tooltip = opts.tooltip || {};
    this.tooltip = this.root_elem.find('.rubixcc-tooltip');
    this.tooltip.hide();

    if(opts && opts.tooltip && opts.tooltip.animate) {
        this.tooltip.addClass('animate');
    }

    this.tooltip.html("");
    this.tooltip.css({
        'font-family': 'Lato, "Lucida Grande", Arial, Helvetica, sans-serif',
        'font-size': '12px',
        'position': 'absolute',
        'background': 'white',
        'color': '#89949B',
        'padding': '10px 15px',
        'display': 'none',
        'pointer-events': 'none',
        'border-radius': '5px',
        'z-index': 100,
        'min-height': 50,
        'user-select': 'none',
        'cursor': 'default',
        'border': '3px solid ' + (opts.tooltip.color ? opts.tooltip.color : '#89949B'),
        'box-shadow': 'rgba(0, 0, 0, 0.2) 2px 4px 8px',
        'background': 'white'
    });

    opts.legend = opts.legend || {};

    this.legend = this.root_elem.find('.rubixcc-legend');
    this.legend.css({
        'font-family': "Lato, 'Lucida Grande', Arial, Helvetica, sans-serif",
        'text-align': 'center',
        'margin-top': opts.legend.top || '-10px',
        'margin-bottom': opts.legend.bottom || '5px',
        'user-select': 'none',
        'display': opts.hideLegend ? 'none' : 'block'
    });

    this.title = this.root_elem.find('.rubixcc-title');
    this.subtitle = this.root_elem.find('.rubixcc-subtitle');

    this.title.css({
        'font-family': "Lato, 'Lucida Grande', Arial, Helvetica, sans-serif",
        'text-align': 'center',
        'user-select': 'none',
        'font-weight': 'bold',
        'font-size': '16px',
        'color': 'steelblue',
        'margin-top': '10px',
        'cursor': 'default'
    });

    this.subtitle.css({
        'font-family': "Lato, 'Lucida Grande', Arial, Helvetica, sans-serif",
        'text-align': 'center',
        'user-select': 'none',
        'font-size': '12px',
        'color': 'steelblue',
        'margin-top': '5px',
        'cursor': 'default',
        'opacity': 0.8,
        'font-weight': 'bold'
    });

    var self = this;
    this.tooltip.on({
        'mouseover': function(e) {
            d3.select(self.elem.find('.overlay').get(0)).node().__onmousemove(e);
        },
        'mousemove': function(e) {
            d3.select(self.elem.find('.overlay').get(0)).node().__onmousemove(e);
        },
        'mouseout': function(e) {
            $(this).hide();
        }
    });

    this.elem.css({
        'width': '100%',
        'height': parseInt(this.root_elem.get(0).style.height),
        'user-select': 'none',
        'cursor': 'default',
        'position': 'relative'
    });
    this.root_elem.css('height', '100%');
    this.opts = opts || {};

    this.data = {};
    this.charts = {};
    this.extent = [];
    this.extent_coordinates = [];
    this.is_touch_device = 'ontouchstart' in document.documentElement;
    this.d3_eventSource = function () {
        var e = d3.event, s;
        while (s = e.sourceEvent) e = s;
        return e;
    }

    this.custom_interpolations = {
        sankey: function(points) {
            var x0 = points[0][0], y0 = points[0][1], x1, y1, x2,
                path = [x0, ",", y0],
                i = 0,
                n = points.length;
            while (++i < n) {
                x1 = points[i][0], y1 = points[i][1], x2 = (x0 + x1) / 2;
                path.push("C", x2, ",", y0, " ", x2, ",", y1, " ", x1, ",", y1);
                x0 = x1, y0 = y1;
            }
            return path.join("");
        }
    };

    this.xlabelcolor = 'steelblue';
    this.ylabelcolor = 'steelblue';

    this.first_time = true;
    this.last_render = null;

    this.data_changed = false;

    this.setup();
};

Rubix.prototype.setup = function() {
    this._setupOpts();
    this._setupOnce();
    this._setupRedraw();

    this.draw();
};

Rubix.prototype.draw = function() {
    if(!this.data_changed) {
        if(this.last_render !== null)
            if(Date.now() - this.last_render < 300) return;
        this.last_render = Date.now();
    }
    this.data_changed = false;
    if(!this.root_elem.is(':visible')) return;
    this._draw();
};

Rubix.prototype.uid = function(type) {
    return 'rubixcc-' + type +'-' + Math.floor(2147483648*Math.random()).toString(36);
};

/** @private */
Rubix.prototype._setupOpts = function() {
    this.opts.theme_style = this.opts.theme_style || $('body').attr('data-theme') || 'light';
    this.opts.theme_style_color = this.opts.gridColor || ((this.opts.theme_style === 'light') ? '#C0D0E0' : '#555');
    this.opts.theme_focus_line_color = this.opts.focusLineColor || ((this.opts.theme_style === 'light') ? '#C0D0E0' : '#888');
    this.opts.tickColor = this.opts.tickColor || ((this.opts.theme_style === 'light') ? '#666' : '#999');

    this.opts.titleColor = this.opts.titleColor || 'steelblue';
    this.opts.subtitleColor = this.opts.subtitleColor || 'steelblue';

    this.opts.legend_color_brightness = this.opts.legend_color_brightness || 0.5;

    this.title.css('color', this.opts.titleColor);
    this.subtitle.css('color', this.opts.subtitleColor);

    if(this.opts.theme_style === 'dark' || this.opts.tooltip.theme_style === 'dark') {
        this.tooltip.css({
            "color": "#aaa",
            "font-weight": "bold",
            "border": "1px solid #222",
            "background-color": "#303030"
        });
    }

    this.opts.margin = this.opts.margin || {};
    this.margin = {
        top    : (this.opts.margin.top >= 0) ? this.opts.margin.top : 25,
        left   : (this.opts.margin.left >= 0) ? this.opts.margin.left : 25,
        right  : (this.opts.margin.right >= 0) ? this.opts.margin.right : 12.5,
        bottom : (this.opts.margin.bottom >= 0) ? this.opts.margin.bottom : 25
    };

    this.opts.draw = this.opts.draw || {};
    this.opts.draw = {
        grid: (this.opts.draw.grid === false) ? false : true
    };

    this.opts.invertAxes = this.opts.invertAxes || false;
    this.opts.axis = this.opts.axis || {};
    this.opts.axis.x = this.opts.axis.x || {};
    this.opts.axis.y = this.opts.axis.y || {};
    this.axis = {
        x: {
            type: this.opts.axis.x.type || 'linear',
            range: this.opts.axis.x.range || '',
            tickCount : this.opts.axis.x.tickCount,
            tickFormat: this.opts.axis.x.tickFormat || '',
            label: this.opts.axis.x.label || '',
            labelColor: this.opts.axis.x.labelColor || 'steelblue'
        },
        y: {
            type: this.opts.axis.y.type || 'linear',
            range: this.opts.axis.y.range || '',
            tickCount : this.opts.axis.y.tickCount,
            tickFormat: this.opts.axis.y.tickFormat || '',
            label: this.opts.axis.y.label || '',
            labelColor: this.opts.axis.y.labelColor || 'steelblue'
        }
    };

    this.xlabelcolor = this.opts.axis.x.labelColor;
    this.ylabelcolor = this.opts.axis.y.labelColor;

    if(this.axis.x.label.length) {
        if(this.opts.invertAxes) {
            this.margin.left += 15;
        } else {
            this.margin.bottom += 15;
        }
    }

    if(this.axis.y.label.length) {
        if(this.opts.invertAxes) {
            this.margin.bottom += 15;
        } else {
            this.margin.left += 15;
        }
    }

    this.margin.defaultLeft = this.margin.left;

    this.opts.tooltip = this.opts.tooltip || {};
    this.opts.tooltip.format = this.opts.tooltip.format || {};
    this.opts.tooltip.abs = this.opts.tooltip.abs || {};
    this.tooltipFormatter = {
        format: {
            x: this.opts.tooltip.format.x || '',
            y: this.opts.tooltip.format.y || ''
        },
        abs: {
            x: (this.opts.tooltip.abs.hasOwnProperty('x')) ? this.opts.tooltip.abs.x : false,
            y: (this.opts.tooltip.abs.hasOwnProperty('y')) ? this.opts.tooltip.abs.y : false
        }
    }

    if(this.opts.invertAxes) {
        var temp = this.axis.x;
        this.axis.x = this.axis.y;
        this.axis.y = temp;

        var temp2 = this.tooltipFormatter.format.x;
        this.tooltipFormatter.format.x = this.tooltipFormatter.format.y;
        this.tooltipFormatter.format.y = temp2;
    }

    if(this.opts.animationSpeed !== 0) {
        this.animationSpeed = this.opts.animationSpeed || 750;
    }

    this.opts.interval = this.opts.start_interval || 0;

    this.stacked = this.opts.stacked || false;
    this.grouped = this.opts.grouped || false;
    this.offset = this.opts.offset || 'zero';
    this.order  = this.opts.order || 'default';
    this.show_markers = this.opts.show_markers || false;

    this.resize = this.opts.resize || 'debounced';

    this.interpolate = this.opts.interpolate || 'linear';
    switch(this.interpolate) {
        case 'sankey':
            this.interpolate = this.custom_interpolations[this.interpolate];
        break;
        default:
            // do nothing
        break;
    }

    this.master_detail = this.opts.master_detail || false;

    this.master_detail_height = this.opts.master_detail_height || 50;
    if(this.master_detail) {
        this.master_detail_margin_bottom = this.margin.bottom;
        this.margin.bottom = (2 * this.margin.bottom) + this.master_detail_height;
    }

    this.opts.title = this.opts.title || '';
    this.opts.subtitle = this.opts.subtitle || '';

    this.title.html(this.opts.title);
    this.subtitle.html(this.opts.subtitle);
    if(this.opts.title.length || this.opts.subtitle.length) {
        this.elem.css('margin-top', '-20px');
    }
    (this.opts.title.length) ? this.title.show() : this.title.hide();
    (this.opts.subtitle.length) ? this.subtitle.show() : this.subtitle.hide();
};

function d3_scale_ordinal_invert(x) {
  // TODO take into account current ranger

  var _range = this.range().concat();
  var _domain = this.domain().concat();

  _range.sort(function(a, b) {
    if(a > b) {
        return 1;
    } else if(a === b) {
        return 0;
    } else {
        return -1;
    }
  })
  _domain.sort(function(a, b) {
    if(a > b) {
        return 1;
    } else if(a === b) {
        return 0;
    } else {
        return -1;
    }
  })
  return _domain[d3.bisect(_range, x) - 1];
}

/** @private */
Rubix.prototype._setupOnce = function() {
    var self = this;
    this.area_stack_data = [];
    this.area_stack = d3.layout.stack();
    this.area_stack.offset(this.offset);
    this.area_stack.order(this.order);
    this.area_stack.values(function(d) {
        return d.values;
    });
    this.area_stack.x(function(d) {
        return d.x;
    });
    this.area_stack.y(function(d) {
        return d.y;
    });
    this.area_stack.out(function(d, y0, y) {
        d.y0 = y0;
        d.y_new = y;
    });
    this.column_stack_data = [];
    this.column_stack = d3.layout.stack();
    this.column_stack.offset('zero');
    this.column_stack.order('default');
    this.column_stack.values(function(d) {
        return d.values;
    });
    this.column_stack.x(function(d) {
        return d.x;
    });
    this.column_stack.y(function(d) {
        return d.y;
    });
    this.column_stack.out(function(d, y0, y) {
        d.y0 = y0;
        d.y_new = y;
    });
};

/** @private */
Rubix.prototype._setupAxis = function(animate) {
    switch(this.axis.x.type) {
        case 'linear':
            this.x = d3.scale.linear();
            this.x.tickFormat(d3.format(this.axis.x.tickFormat));

            var range = [0, this.width];
            var masterRange = [0, this.width];
            if(this.master_detail) {
                this.x2 = d3.scale.linear();
            }

            if(this.axis.x.range === 'round') {
                this.x.rangeRound(range);

                if(this.master_detail) {
                    this.x2.rangeRound(masterRange);
                }
            } else {
                this.x.range(range);

                if(this.master_detail) {
                    this.x2.range(masterRange);
                }
            }
        break;
        case 'ordinal':
            this.x = d3.scale.ordinal();
            this.x.invert = d3_scale_ordinal_invert.bind(this.x);

            var range = [0, this.width];
            var masterRange = [0, this.width];
            if(this.master_detail) {
                this.canvas.select('.noData').text('Master-Detail chart only for quantitative scales.');
                throw new Error('Master-Detail chart only for quantitative scales.');
            }

            if(this.axis.x.range === 'round') {
                this.x.rangeRoundBands(range);
            } else if(this.axis.x.range == 'column' || this.axis.x.range == 'bar') {
                this.x.rangeRoundBands(range, 0.35);
            } else {
                this.x.rangePoints(range);

                if(this.master_detail) {
                    this.x2.rangePoints(range);
                }
            }
        break;
        case 'datetime':
            this.x = d3.time.scale();
            this.x.ticks(d3.time.year, 50);
            this.x.tickFormat(d3.format(this.axis.x.tickFormat));

            var range = [0, this.width];
            var masterRange = [0, this.width];
            if(this.master_detail) {
                this.x2 = d3.time.scale();
            }

            if(this.axis.x.range === 'round') {
                this.x.rangeRound(range);

                if(this.master_detail) {
                    this.x2.rangeRound(masterRange);
                }
            } else {
                this.x.range(range);

                if(this.master_detail) {
                    this.x2.range(masterRange);
                }
            }
        break;
        default:
            throw new Error('Unknown Scale for X Axis: ' + this.axis.x.type);
        break;
    }

    switch(this.axis.y.type) {
        case 'linear':
            this.y = d3.scale.linear();
            this.y.clamp(true);
            this.y.tickFormat(d3.format(this.axis.y.tickFormat));

            var range = [this.height, 0];
            var masterRange = [this.master_detail_height, 0];
            if(this.master_detail) {
                this.y2 = d3.scale.linear();
                this.y2.clamp(true);
            }

            if(this.axis.y.range === 'round') {
                this.y.rangeRound(range);

                if(this.master_detail) {
                    this.y2.rangeRound(masterRange);
                }
            } else {
                this.y.range(range);

                if(this.master_detail) {
                    this.y2.range(masterRange);
                }
            }
        break;
        case 'ordinal':
            this.y = d3.scale.ordinal();
            this.y.invert = d3_scale_ordinal_invert.bind(this.y);

            var range = [this.height, 0];
            var masterRange = [this.master_detail_height, 0];
            if(this.master_detail) {
                this.y2 = d3.scale.ordinal();
                this.y2.invert = d3_scale_ordinal_invert.bind(this.y2);
            }

            if(this.axis.y.range === 'round') {
                this.y.rangeRoundBands(range);

                if(this.master_detail) {
                    this.y2.rangeRoundBands(masterRange);
                }
            } else if(this.axis.y.range == 'column' || this.axis.y.range == 'bar') {
                this.y.rangeRoundBands(range, 0.35);
            } else {
                this.y.rangePoints(range);

                if(this.master_detail) {
                    this.y2.rangePoints(masterRange);
                }
            }
        break;
        case 'datetime':
            this.y = d3.time.scale();
            this.y.tickFormat(d3.format(this.axis.y.tickFormat));
            this.y.clamp(true);

            var range = [this.height, 0];
            var masterRange = [this.master_detail_height, 0];
            if(this.master_detail) {
                this.y2 = d3.time.scale();
                this.y2.clamp(true);
            }

            if(this.axis.y.range === 'round') {
                this.y.rangeRound(range);

                if(this.master_detail) {
                    this.y2.rangeRound(masterRange);
                }
            } else {
                this.y.range(range);

                if(this.master_detail) {
                    this.y2.range(masterRange);
                }
            }
        break;
        default:
            throw new Error('Unknown Scale for X Axis: ' + this.axis.y.type);
        break;
    }

    this.fontSize = 10;
    var yTicks = Math.floor((this.height / (this.fontSize + 3)) / 2);

    this.xAxis = d3.svg.axis();
    this.xAxis.scale(this.x).orient('bottom');

    if(this.master_detail) {
        this.xAxis2 = d3.svg.axis();
        this.xAxis2.scale(this.x2).orient('bottom');
    }

    if(this.axis.x.range === 'round' && (this.axis.x.type === 'linear' || this.axis.x.type === 'datetime')) {
        if(this.axis.x.type === 'linear') {
            this.xAxis.tickFormat(d3.format(this.axis.x.tickFormat));
        } else if(this.axis.x.type === 'datetime') {
            this.xAxis.tickFormat(d3.time.format(this.axis.x.tickFormat));
        }

        if(this.master_detail) {
            if(this.axis.x.type === 'linear') {
                this.xAxis2.tickFormat(d3.format(this.axis.x.tickFormat));
            } else {
                this.xAxis2.tickFormat(d3.time.format(this.axis.x.tickFormat));
            }
        }
    }

    if(this.axis.x.tickFormat) {
        if(this.axis.x.type === 'linear') {
            this.xAxis.tickFormat(d3.format(this.axis.x.tickFormat));
        } else if(this.axis.x.type === 'datetime') {
            this.xAxis.tickFormat(d3.time.format(this.axis.x.tickFormat));
        }

        if(this.master_detail) {
            if(this.axis.x.type === 'linear') {
                this.xAxis2.tickFormat(d3.format(this.axis.x.tickFormat));
            } else {
                this.xAxis2.tickFormat(d3.time.format(this.axis.x.tickFormat));
            }
        }
    }

    this.xGrid = d3.svg.axis();
    this.xGrid.scale(this.x).orient('bottom').ticks(5).tickSize(-this.height, 0, 0).tickFormat('');

    if(this.axis.x.tickCount !== undefined) {
        this.xGrid.ticks(this.axis.x.tickCount);
    }

    if(this.master_detail) {
        this.xGrid2 = d3.svg.axis();
        this.xGrid2.scale(this.x2).orient('bottom').ticks(5).tickSize(-this.master_detail_height, 0, 0).tickFormat('');
    }

    this.yAxis = d3.svg.axis();
    this.yAxis.scale(this.y).orient('left');

    this.yAxis.ticks(yTicks);

    if(this.axis.y.range === 'round' && (this.axis.y.type === 'linear' || this.axis.y.type === 'datetime')) {
        if(this.axis.y.type === 'linear') {
            this.yAxis.tickFormat(d3.format(this.axis.y.tickFormat));
        } else if(this.axis.y.type === 'datetime') {
            this.yAxis.tickFormat(d3.time.format(this.axis.y.tickFormat));
        }
    }

    if(this.axis.y.tickFormat) {
        if(this.axis.y.type === 'linear') {
            this.yAxis.tickFormat(d3.format(this.axis.y.tickFormat));
        } else if(this.axis.y.type === 'datetime') {
            this.yAxis.tickFormat(d3.time.format(this.axis.y.tickFormat));
        }
    }

    this.yGrid = d3.svg.axis();
    this.yGrid.scale(this.y).orient('left').ticks(5).tickSize(-this.width, 0, 0).tickFormat('');
    if(this.axis.y.tickCount !== undefined) {
        this.yGrid.ticks(this.axis.y.tickCount);
    }
    this.xAxisGroup = this.axis_group.append('g');
    this.xAxisGroup.attr('class', 'x axis');
    this.xAxisGroup.attr('transform', 'translate(0, '+this.height+')');
    this.xAxisGroup.call(this.xAxis);
    this.xAxisGroup.select('path').style('fill', 'none').style('stroke', this.opts.theme_style_color).style('shape-rendering', 'crispEdges');
    this.xAxisGroup.selectAll('line').attr('fill', 'none').attr('stroke', this.opts.theme_style_color).style('shape-rendering', 'crispEdges');

    if(this.master_detail) {
        this.xAxisGroup2 = this.md_root.append('g');
        this.xAxisGroup2.attr('class', 'x axis');
        // this.xAxisGroup2.attr('transform', 'translate(0, '+ this.master_detail_height +')');
        this.xAxisGroup2.call(this.xAxis2);
        this.xAxisGroup2.select('path').style('fill', 'none');
        this.xAxisGroup2.select('line').style('fill', 'none').style('stroke', this.opts.theme_style_color).style('shape-rendering', 'crispEdges');
        this.xAxisGroup2.selectAll('line').attr('fill', 'none').attr('stroke', this.opts.theme_style_color).style('shape-rendering', 'crispEdges');
    }

    this.yAxisGroup = this.axis_group.append('g');
    this.yAxisGroup.attr('class', 'y axis');
    this.yAxisGroup.call(this.yAxis);
    this.yAxisGroup.select('path').style('fill', 'none').style('stroke', this.opts.theme_style_color).style('shape-rendering', 'crispEdges');
    this.yAxisGroup.selectAll('line').attr('fill', 'none').attr('stroke', this.opts.theme_style_color).style('shape-rendering', 'crispEdges');

    if(this.opts.draw.grid) {
        this.xGridGroup = this.grid_group.append('g');
        this.xGridGroup.attr('class', 'x grid');
        this.xGridGroup.attr('transform', 'translate(0,'+this.height+')');
        this.xGridGroup.call(this.xGrid);
        this.xGridGroup.selectAll('path').style('stroke-width', 0);
        this.xGridGroup.selectAll('.tick').style('stroke', this.opts.theme_style_color).style('opacity', 0.7);
    }

    if(this.master_detail) {
        this.xGridGroup2 = this.md_root.select('.md-grid').append('g');
        this.xGridGroup2.attr('class', 'x grid');
        this.xGridGroup2.attr('transform', 'translate(0,'+this.master_detail_height+')');
        this.xGridGroup2.call(this.xGrid2);
        this.xGridGroup2.selectAll('path').style('stroke-width', 0);
        this.xGridGroup2.selectAll('.tick').style('stroke', 'lightgray').style('opacity', 0.7);
    }

    if(this.opts.draw.grid) {
        this.yGridGroup = this.grid_group.append('g');
        this.yGridGroup.attr('class', 'y grid');
        this.yGridGroup.call(this.yGrid);
        this.yGridGroup.selectAll('path').style('stroke-width', 0);
        this.yGridGroup.selectAll('.tick').style('stroke', this.opts.theme_style_color).style('opacity', 0.7);
    }

    this.resetAxis(animate);

    if(this.master_detail) {
        if(this.axis.x.type === 'ordinal') {
            this.canvas.select('.noData').text('Master-Detail chart only for quantitative scales.');
            throw new Error('Master-Detail chart only for quantitative scales.');
        }
        this.brush = d3.svg.brush();
        this.brush.x(this.x2);
        var self = this;
        if(this.axis.x.type === 'datetime') {
            this.brush.on('brushend', function() {
                if(self.is_touch_device) {
                    var coordinates = d3.touches(this, self.d3_eventSource().changedTouches)[0];
                } else {
                    var coordinates = d3.mouse(this);
                }
                var x = coordinates[0];

                if(self.brush.empty()) {
                    var current_x0 = self.x2(+new Date(self.extent[0]));
                    var current_x1 = self.x2(+new Date(self.extent[1]));

                    var distance_x0_x = current_x0 - x;

                    var final_extent = [];
                    if(distance_x0_x < 0) {
                        var start = current_x0 + Math.abs(distance_x0_x);
                        var finish = current_x1 + Math.abs(distance_x0_x);
                        var max_extent = self.x2(+new Date(self.x2.domain()[1]));
                        if(finish > max_extent) {
                            start -= (finish - max_extent);
                            finish = max_extent;
                            final_extent = [
                                self.x2.invert(start),
                                self.x2.invert(finish)
                            ];
                        } else {
                            var diff = Math.abs(start - finish);
                            final_extent = [
                                self.x2.invert(start - diff/2),
                                self.x2.invert(finish - diff/2)
                            ];
                        }
                    } else {
                        var start = current_x0 - Math.abs(distance_x0_x);
                        var finish = current_x1 - Math.abs(distance_x0_x);

                        var diff = Math.abs(start - finish);
                        var final_start = start - diff/2;
                        var final_finish = finish - diff/2;

                        var min_extent = self.x2(+new Date(self.x2.domain()[0]));

                        if(final_start < min_extent) {
                            final_finish += (min_extent - final_start);
                            final_start = min_extent;
                        }
                        final_extent = [
                            self.x2.invert(final_start),
                            self.x2.invert(final_finish)
                        ];
                    }

                    self.extent = final_extent;
                    self.brush.extent(self.extent);
                    self.brush_path.call(self.brush);
                    self._brush();
                }


                var brush_x_pos = self.brush_path.select('.extent').attr('x');
                var brush_width = self.brush_path.select('.extent').attr('width');
                var brush_height = self.brush_path.select('.extent').attr('height');

                self.brush_path.select('.left-extent').attr('height', brush_height).attr('width', brush_x_pos).attr('x', 0);
                var brush_r_x_pos = parseFloat(brush_x_pos)+parseFloat(brush_width);
                self.brush_path.select('.right-extent').attr('height', brush_height).attr('x', brush_r_x_pos).attr('width', self.md_root.attr('width') - brush_r_x_pos);
                self.brush_path.select('.left-border').attr('width', 1).attr('x', brush_x_pos);
                self.brush_path.select('.left-top-border').attr('width', brush_x_pos);
                self.brush_path.select('.right-border').attr('width', 1).attr('x', brush_r_x_pos);
                self.brush_path.select('.right-top-border').attr('x', brush_r_x_pos).attr('width', self.md_root.attr('width') - brush_r_x_pos);
                self.brush_path.select('.bottom-border').attr('x', brush_x_pos).attr('width', brush_width).attr('transform', 'translate(0, ' + (self.master_detail_height+5) + ')');
            });
        } else {
            this.brush.on('brushend', function() {
                if(self.is_touch_device) {
                    var coordinates = d3.touches(this, self.d3_eventSource().changedTouches)[0];
                } else {
                    var coordinates = d3.mouse(this);
                }
                var x = self.x2.invert(coordinates[0]);

                if(self.brush.empty()) {
                    var current_x0 = self.extent[0];
                    var current_x1 = self.extent[1];

                    var distance_x0_x = current_x0 - x;

                    var final_extent = [];
                    if(distance_x0_x < 0) {
                        var start = current_x0 + Math.abs(distance_x0_x);
                        var finish = current_x1 + Math.abs(distance_x0_x);
                        var max_extent = self.x2.domain()[1];
                        if(finish > max_extent) {
                            start -= (finish - max_extent);
                            finish = max_extent;
                            final_extent = [
                                start,
                                finish
                            ];
                        } else {
                            var diff = Math.abs(start - finish);
                            final_extent = [
                                start - diff/2,
                                finish - diff/2
                            ];
                        }
                    } else {
                        var start = current_x0 - Math.abs(distance_x0_x);
                        var finish = current_x1 - Math.abs(distance_x0_x);

                        var diff = Math.abs(start - finish);
                        var final_start = start - diff/2;
                        var final_finish = finish - diff/2;

                        var min_extent = self.x2.domain()[0];

                        if(final_start < min_extent) {
                            final_finish += (min_extent - final_start);
                            final_start = min_extent;
                        }
                        final_extent = [
                            final_start,
                            final_finish
                        ];
                    }

                    self.extent = final_extent;
                    self.brush.extent(self.extent);
                    self.brush_path.call(self.brush);
                    self._brush();
                }

                var brush_x_pos = self.brush_path.select('.extent').attr('x');
                var brush_width = self.brush_path.select('.extent').attr('width');
                var brush_height = self.brush_path.select('.extent').attr('height');

                self.brush_path.select('.left-extent').attr('height', brush_height).attr('width', brush_x_pos).attr('x', 0);
                var brush_r_x_pos = parseFloat(brush_x_pos)+parseFloat(brush_width);
                self.brush_path.select('.right-extent').attr('height', brush_height).attr('x', brush_r_x_pos).attr('width', self.md_root.attr('width') - brush_r_x_pos);
                self.brush_path.select('.left-border').attr('width', 1).attr('x', brush_x_pos);
                self.brush_path.select('.left-top-border').attr('width', brush_x_pos);
                self.brush_path.select('.right-border').attr('width', 1).attr('x', brush_r_x_pos);
                self.brush_path.select('.right-top-border').attr('x', brush_r_x_pos).attr('width', self.md_root.attr('width') - brush_r_x_pos);
                self.brush_path.select('.bottom-border').attr('x', brush_x_pos).attr('width', brush_width).attr('transform', 'translate(0, ' + (self.master_detail_height+5) + ')');
            });
        }
        this.brush.on('brush', function() {
            var type = self.d3_eventSource().type;
            if(type === 'mousemove' || type === 'touchmove') {
                $(window).trigger('rubix.sidebar.off');
                self._brush(true, 'root');
            }
            var brush_x_pos = self.brush_path.select('.extent').attr('x');
            var brush_width = self.brush_path.select('.extent').attr('width');
            var brush_height = self.brush_path.select('.extent').attr('height');

            self.brush_path.select('.left-extent').attr('height', brush_height).attr('width', brush_x_pos).attr('x', 0);
            var brush_r_x_pos = parseFloat(brush_x_pos)+parseFloat(brush_width);
            self.brush_path.select('.right-extent').attr('height', brush_height).attr('x', brush_r_x_pos).attr('width', self.md_root.attr('width') - brush_r_x_pos);
            self.brush_path.select('.left-border').attr('width', 1).attr('x', brush_x_pos);
            self.brush_path.select('.left-top-border').attr('width', brush_x_pos);
            self.brush_path.select('.right-border').attr('width', 1).attr('x', brush_r_x_pos);
            self.brush_path.select('.right-top-border').attr('x', brush_r_x_pos).attr('width', self.md_root.attr('width') - brush_r_x_pos);
            self.brush_path.select('.bottom-border').attr('x', brush_x_pos).attr('width', brush_width).attr('transform', 'translate(0, ' + (self.master_detail_height+5) + ')');
        });
        if(this.extent.length) {
            this.brush.extent(this.extent);
            if(!this.extent_coordinates.length) {
                if(this.axis.x.type === 'datetime' || this.axis.y.type === 'datetime') {
                    this.extent_coordinates = [
                        this.x2(+new Date(this.extent[0])),
                        this.x2(+new Date(this.extent[1]))
                    ];
                } else {
                    this.extent_coordinates = [
                        this.x2(this.extent[0]),
                        this.x2(this.extent[1])
                    ];
                }
            }
            this._brush();
        }
    }
};

Rubix.prototype._setupSeries = function() {
    this.grid_group = this.root.append('g').attr('class', 'grid_group');
    this.axis_group = this.root.append('g').attr('class', 'axis_group');

    if(this.opts.hideGrid) {
        this.grid_group.style('display', 'none');
    }
    if(this.opts.hideAxisAndGrid) {
        this.grid_group.style('display', 'none');
        this.axis_group.style('display', 'none');
    }

    this.root_cb_series = this.root.append('g').attr('class', 'cb_series');
    this.root_stacked_area_series = this.root.append('g').attr('class', 'stacked_area_series');
    this.root_area_series = this.root.append('g').attr('class', 'area_series');
    this.root_line_series = this.root.append('g').attr('class', 'line_series');

    this.focus_line_group = this.root.append('g').attr('class', 'focus_line_group');

    this.symbols_group = this.root.append('g').attr('class', 'symbols');
    this.focus_group = this.root.append('g').attr('class', 'focus_group');

    if(this.master_detail) {
        this.md_root_stacked_area_series = this.md_layers.append('g').attr('class', 'md_stacked_area_series');
        this.md_root_area_series = this.md_layers.append('g').attr('class', 'md_area_series');
        this.md_root_line_series = this.md_layers.append('g').attr('class', 'md_line_series');
    }
};

Rubix.prototype.resetExtent = function() {
    var curr_extent = this.brush.extent();
    if(curr_extent.length) {
        var start = this.extent_coordinates[0];
        var finish = this.extent_coordinates[1];
        if(this.axis.x.type === 'datetime' || this.axis.y.type === 'datetime') {
            this.extent = [this.x2.invert(+new Date(start)), this.x2.invert(+new Date(finish))];
        } else {
            this.extent = [this.x2.invert(start), this.x2.invert(finish)];
        }
        this.brush.extent(this.extent);
        this._brush_nochange();
    }
};

Rubix.prototype._brush_nochange = function() {
    try {
        this.x.domain(this.extent);
        this.callAxis();
        for(var i in this.charts) {
            this.charts[i].noRedraw(this);
        }
    } catch(e) {
        // do nothing
    }
};

Rubix.prototype._brush = function(record, type) {
    var extent = this.brush.empty() ? this.x2.domain() : this.brush.extent();
    if(this.axis.x.type === 'datetime' || this.axis.y.type === 'datetime') {
        this.extent = extent;
        if(record && type === 'root') {
            this.extent_coordinates = [
                this.x2(+new Date(this.extent[0])),
                this.x2(+new Date(this.extent[1]))
            ];
        }
    } else {
        this.extent = extent;
        if(record && type === 'root') {
            this.extent_coordinates = [
                this.x2(this.extent[0]),
                this.x2(this.extent[1])
            ];
        }
    }
    this._brush_nochange();
};

Rubix.prototype._setupOrdinalAxis = function(forced) {
    var data = {}, o_data = {};
    var _data = [], _data1 = [], extentX, extentY, rdata;
    this.crosshair_data = [], _o_data = [];
    var temp = {}, others = {};
    if(this.axis.x.type === 'ordinal' || this.axis.y.type === 'ordinal') {
        for(var series in this.data) {
            rdata = this.data[series];
            var chart = this.charts[series];
            for(var i=0; i<rdata.length; i++) {
                var x = rdata[i].x;
                var y0 = 0;
                if(this.stacked) {
                    if(rdata[i].hasOwnProperty('y0')) {
                        var y0 = rdata[i].y0;
                    }
                    if(rdata[i].hasOwnProperty('y_new')) {
                        if(this.grouped && chart.type === 'column_series') {
                            var y = rdata[i].y_new;
                        } else {
                            var y = rdata[i].y_new + y0;
                        }
                    } else {
                        var y = rdata[i].y;
                    }
                } else {
                    var y = rdata[i].y;
                }
                _data1.push(y);
                if(!data.hasOwnProperty(x)) {
                    data[x] = [];
                }
                data[x].push(y);
                if(forced) {
                    if(!o_data.hasOwnProperty(y)) {
                        o_data[y] = [];
                    }
                    o_data[y].push(x);
                }

                if(!temp.hasOwnProperty(x)) {
                    temp[x] = {};
                }
                temp[x][series] = y;
                if(!others.hasOwnProperty(x)) {
                    others[x] = {};
                }
                others[x][series] = {
                    y0: y0
                };
            }
        }

        for(var point in data) {
            _data.push(point);
        }
        if(forced) {
            for(var point in o_data) {
                _o_data.push(point);
            }
        }

        if(this.stacked) {
            var _minY = d3.min(_data1, function(d) {
                if(isNaN(d)) {
                    return 0;
                }
                return d;
            });

            _minY = _minY < 0 ? _minY : 0;

            var extentY = [_minY, d3.max(_data1, function(d) {
                if(isNaN(d)) {
                    return 0;
                }
                return d;
            })];
        } else {
            var extentY = d3.extent(_data1);
        }
        var yMin = extentY[0];
        var yMax = extentY[1];

        if(this.opts.invertAxes) {
            if(this.axis.y.tickFormat.length) {
                for(var i=0; i<_data.length; i++) {
                    _data[i] = +_data[i];
                }
                _data.sort(function(a, b) {
                    if(a > b) {
                        return 1;
                    } else if(a === b) {
                        return 0
                    } else {
                        return -1;
                    }
                });
            }

            if(forced) {
                if(this.axis.x.tickFormat.length) {
                    for(var i=0; i<_o_data.length; i++) {
                        _o_data[i] = +_o_data[i];
                    }
                    _o_data.sort(function(a, b) {
                        if(a > b) {
                            return 1;
                        } else if(a === b) {
                            return 0
                        } else {
                            return -1;
                        }
                    });
                }

                this.x.domain(_o_data);
                this.y.domain(_data.reverse());

                if(this.master_detail) {
                    this.x2.domain(_o_data);
                    this.y2.domain(_data);
                }
            } else {
                this.x.domain([yMin, yMax]);
                this.y.domain(_data.reverse());

                if(this.master_detail) {
                    this.x2.domain([yMin, yMax]);
                    this.y2.domain(_data);
                }
            }
        } else {
            if(this.axis.x.tickFormat.length) {
                for(var i=0; i<_data.length; i++) {
                    _data[i] = +_data[i];
                }
                _data.sort(function(a, b) {
                    if(a > b) {
                        return 1;
                    } else if(a === b) {
                        return 0
                    } else {
                        return -1;
                    }
                });
            }

            if(forced) {
                if(this.axis.y.tickFormat.length) {
                    for(var i=0; i<_o_data.length; i++) {
                        _o_data[i] = +_o_data[i];
                    }
                    _o_data.sort(function(a, b) {
                        if(a > b) {
                            return 1;
                        } else if(a === b) {
                            return 0
                        } else {
                            return -1;
                        }
                    });
                }

                this.x.domain(_data);
                this.y.domain(_o_data);

                if(this.master_detail) {
                    this.x2.domain(_data);
                    this.y2.domain(_o_data);
                }
            } else {
                this.x.domain(_data);
                this.y.domain([yMin, yMax]);

                if(this.master_detail) {
                    this.x2.domain(_data);
                    this.y2.domain([yMin, yMax]);
                }
            }
        }

        for(var x in temp) {
            if(this.opts.invertAxes) {
                var _x = this.y(x);
                this.crosshair_data.push({
                    x: +_x,
                    y: temp[x],
                    others: others[x]
                });
            } else {
                var _x = this.x(x);
                this.crosshair_data.push({
                    x: +_x,
                    y: temp[x],
                    others: others[x]
                });
            }
        }

        this.crosshair_data.sort(function(a, b) {
            if(a.x > b.x) {
                return 1;
            } else if(a.x === b.x) {
                return 0;
            } else {
                return -1;
            }
        });
    }
};

Rubix.prototype._setupLinearAxis = function() {
    var xMin=null, xMax=null, yMin=null, yMax=null;
    var data, extentX, extentY;
    this.crosshair_data = [];
    var temp = {}, others = {};
    for(var series in this.data) {
        data = this.data[series];
        extentX = d3.extent(data, function(d) {return d.x});
        if(this.stacked) {
            var _minY = d3.min(data, function(d) {
                var val = d.y0 + d.y_new;
                if(isNaN(val)) {
                    if(d.y) return d.y;
                    return 0;
                }
                return val;
            });

            _minY = _minY < 0 ? _minY : 0;

            extentY = [_minY, d3.max(data, function(d) {
                var val = d.y0 + d.y_new;
                if(isNaN(val)) {
                    if(d.y) return d.y;
                    return 0;
                }
                return val;
            })];
        } else {
            extentY = d3.extent(data, function(d) {return d.y;});
        }

        if(xMin === null) {
            xMin = extentX[0];
            xMax = extentX[1];
        } else {
            if(xMin >= extentX[0]) xMin = extentX[0];
            if(xMax <= extentX[1]) xMax = extentX[1];
        }
        if(yMin === null) {
            yMin = extentY[0];
            yMax = extentY[1];
        } else {
            if(yMin >= extentY[0]) yMin = extentY[0];
            if(yMax <= extentY[1]) yMax = extentY[1];
        }

        for(var i=0; i<data.length; i++) {
            var x = data[i].x;
            var y0 = 0;
            if(this.stacked) {
                if(data[i].hasOwnProperty('y0')) {
                    var y0 = data[i].y0;
                }
                if(data[i].hasOwnProperty('y_new')) {
                    var y = data[i].y_new + y0;
                } else {
                    var y = data[i].y;
                }
            } else {
                var y = data[i].y;
            }

            if(!temp.hasOwnProperty(x)) {
                temp[x] = {};
            }
            temp[x][series] = y;
            if(!others.hasOwnProperty(x)) {
                others[x] = {};
            }
            others[x][series] = {
                y0: y0
            };
        }
    }

    if(this.opts.invertAxes) {
        this.x.domain([yMin, yMax]);
        this.y.domain([xMax, xMin]);
        if(this.master_detail) {
            this.x2.domain([yMin, yMax]);
            this.y2.domain([xMax, xMin]);
        }
    } else {
        this.x.domain([xMin, xMax]);
        this.y.domain([yMin, yMax]);
        if(this.master_detail) {
            this.x2.domain([xMin, xMax]);
            this.y2.domain([yMin, yMax]);
        }
    }

    for(var x in temp) {
        this.crosshair_data.push({
            x: +x,
            y: temp[x],
            others: others[x]
        });
    }

    this.crosshair_data.sort(function(a, b) {
        if(a.x > b.x) {
            return 1;
        } else if(a.x === b.x) {
            return 0;
        } else {
            return -1;
        }
    });
};

Rubix.prototype.resetAxis = function(animate) {
    try {
        if(this.axis.x.type === 'linear' && this.axis.y.type === 'linear') {
            this._setupLinearAxis();
        } else if(
            (this.axis.x.type === 'datetime' && this.axis.y.type === 'linear')
        ||  (this.axis.x.type === 'linear' && this.axis.y.type === 'datetime')
        ) {
            this._setupLinearAxis();
        } else if(this.axis.x.type === 'ordinal' && this.axis.y.type === 'ordinal') {
            this._setupOrdinalAxis(true);
        } else if(this.axis.x.type === 'ordinal' || this.axis.y.type === 'ordinal') {
            this._setupOrdinalAxis();
        }

        this.callAxis(animate);
    } catch(e) {
        // do nothing
    }
};

Rubix.prototype.callAxis = function(animate) {
    if(animate) {
        var t = this.root.transition().duration(this.animationSpeed);
        if(this.master_detail) {
            var z = this.md_root.transition().duration(this.animationSpeed);
        }
    } else {
        var t = this.root;
        if(this.master_detail) {
            var z = this.md_root;
        }
    }

    if(this.hasData) {
        if(this.axis.x.type === 'ordinal') {
            var domain = this.x.domain();
            if((this.axis.x.tickCount !== undefined) && (domain.length > this.axis.x.tickCount)) {
                var self = this;
                var feed = d3.range(domain.length).map(function(d) {
                   if(d % self.axis.x.tickCount === 0)
                      return domain[d];
                   return null;
                });

                var cleanedFeed = [];
                for(var i=0; i<feed.length; i++) {
                    if(feed[i] === null) continue;
                    cleanedFeed.push(feed[i]);
                }
                this.xAxis.tickValues(cleanedFeed);
            }
        } else {
            if(this.axis.x.tickCount !== undefined) {
                this.xAxis.ticks(this.axis.x.tickCount);

                if(this.master_detail) {
                    this.xAxis2.ticks(this.axis.x.tickCount);
                }
            }
        }

        if(this.axis.y.type === 'ordinal' || this.axis.x.type === 'linear' || this.axis.x.type === 'datetime') {
            var domain = this.y.domain();
            if((this.axis.y.tickCount !== undefined) && (domain.length > this.axis.y.tickCount)) {
                var self = this;
                var feed = d3.range(domain.length).map(function(d) {
                   if(d % self.axis.y.tickCount === 0) {
                      return domain[d];
                   }
                   return '';
                });

                this.yAxis.tickValues(feed);
            }
        } else {
            if(this.axis.y.tickCount !== undefined) {
                this.yAxis.ticks(this.axis.y.tickCount);
            }
        }

        this.canvas.select('.noData').style('display', 'none');
        try {
            t.selectAll('.x.axis').style('display', null).call(this.xAxis);
            t.selectAll('.y.axis').style('display', null).call(this.yAxis);
            t.selectAll('.x.grid').style('display', null).call(this.xGrid);
            t.selectAll('.y.grid').style('display', null).call(this.yGrid);
        } catch(e) {
            // do nothing
        }
        this.xAxisGroup.selectAll('line').attr('fill', 'none').attr('stroke', this.opts.theme_style_color).style('shape-rendering', 'crispEdges');
        this.yAxisGroup.selectAll('line').attr('fill', 'none').attr('stroke', this.opts.theme_style_color).style('shape-rendering', 'crispEdges');

        if(this.opts.draw.grid) {
            this.xGridGroup.selectAll('path').style('stroke-width', 0);
            this.xGridGroup.selectAll('.tick').style('stroke', this.opts.theme_style_color).style('opacity', 0.7);
            this.yGridGroup.selectAll('path').style('stroke-width', 0);
            this.yGridGroup.selectAll('.tick').style('stroke', this.opts.theme_style_color).style('opacity', 0.7);
        }

        this.xAxisGroup.selectAll('text').style('font', (this.fontSize+1)+'px Lato, "Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, Helvetica, sans-serif').style('direction', 'ltr').style('fill', this.opts.tickColor).style('color', this.opts.tickColor).style('font-style', '').style('font-variant', '').style('font-weight', '').style('line-height', '');
        this.yAxisGroup.selectAll('text').style('font', (this.fontSize+1)+'px Lato, "Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, Helvetica, sans-serif').style('direction', 'ltr').style('fill', this.opts.tickColor).style('color', this.opts.tickColor).style('font-style', '').style('font-variant', '').style('font-weight', '').style('line-height', '');
        if(this.opts.theme_style === 'dark') {
            this.xAxisGroup.selectAll('text').style('font-weight', 'bold');
            this.yAxisGroup.selectAll('text').style('font-weight', 'bold');
        }
    } else {
        this.canvas.select('.noData').style('display', null);
        t.selectAll('.x.axis').style('display', 'none');
        t.selectAll('.y.axis').style('display', 'none');
        t.selectAll('.x.grid').style('display', 'none');
        t.selectAll('.y.grid').style('display', 'none');
    }
    if(this.master_detail) {
        if(this.hasData) {
            this.canvas.select('.noData').style('display', 'none');
            z.selectAll('.x.axis').style('display', null).call(this.xAxis2);
            z.selectAll('.x.grid').style('display', null).call(this.xGrid2);
            if(this.xAxisGroup2) {
                this.xAxisGroup2.selectAll('line').attr('fill', 'none').attr('stroke', this.opts.theme_style_color).style('shape-rendering', 'crispEdges');
                this.xGridGroup2.selectAll('path').style('stroke-width', 0);
                this.xGridGroup2.selectAll('.tick').style('stroke', this.opts.theme_style_color).style('opacity', 0.7);
                this.xAxisGroup2.selectAll('text').style('font', (this.fontSize+1)+'px Lato, "Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, Helvetica, sans-serif').style('direction', 'ltr').style('fill', this.opts.tickColor).style('color', this.opts.tickColor).style('font-style', '').style('font-variant', '').style('font-weight', '').style('line-height', '');
                if(this.opts.theme_style === 'dark') {
                    this.xAxisGroup2.selectAll('text').style('font-weight', 'bold');
                }
            }
            if(this.yAxisGroup2) {
                this.yAxisGroup2.selectAll('text').style('font', (this.fontSize+1)+'px Lato, "Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, Helvetica, sans-serif').style('direction', 'ltr').style('fill', this.opts.tickColor).style('color', this.opts.tickColor).style('font-style', '').style('font-variant', '').style('font-weight', '').style('line-height', '');
                if(this.opts.theme_style === 'dark') {
                    this.yAxisGroup.selectAll('text').style('font-weight', 'bold');
                }
            }
        } else {
            this.canvas.select('.noData').style('display', null);
            z.selectAll('.x.axis').style('display', 'none');
            z.selectAll('.y.axis').style('display', 'none');
            z.selectAll('.x.grid').style('display', 'none');
            z.selectAll('.y.grid').style('display', 'none');
        }
    }


    if(this.opts.hideYAxis) {
        this.yAxisGroup.style('display', 'none');
    }
    if(this.opts.hideXAxis) {
        this.xAxisGroup.style('display', 'none');
    }
    if(this.opts.hideXAxisTickLines) {
        this.xGridGroup.style('display', 'none');
    }
    if(this.opts.hideYAxisTickLines) {
        this.yGridGroup.style('display', 'none');
    }
};

/** @private */
Rubix.prototype._setupRedraw = function() {
    var self = this;

    $(window).on('orientationchange.'+this.master_id, function() {
        self.draw();
    });

    $(window).on('rubix.redraw.'+this.master_id, function() {
        self.draw();
    });

    switch(this.resize) {
        case 'always':
            $(window).on('resize.rubix.'+this.master_id, function() {
                self.draw();
            });
        break;
        case 'debounced':
            $(window).on('debouncedresize.rubix.'+this.master_id, function() {
                self.draw();
            });
        break;
        case 'throttled':
            $(window).on('throttledresize.rubix.'+this.master_id, function() {
                self.draw();
            });
        break;
        default:
            throw new Error('Unknown resize type!');
        break;
    }
};

/**
 * @private
 */
Rubix.prototype._draw = function() {
    this._cleanElement();
    this._setSize();
    this._setupCanvas();
    this._setupRoot();
    this._setupSeries();
    this._setupAxis();

    for(var i in this.charts) {
        this.charts[i].redraw(this);
    }
};

/** @private */
Rubix.prototype._cleanElement = function() {
    this.elem.children().remove();
};

/** @private */
Rubix.prototype._setSize = function() {
    this.outerWidth  = this.elem.width();
    this.outerHeight = this.elem.height();

    if(this.opts.hideYAxis) {
        this.margin.left = 0;
        this.margin.right = 0;
    }

    if(this.opts.hideAxisAndGrid) {
        this.margin.left = 0;
        this.margin.right = 0;
    }

    this.width  = this.outerWidth - this.margin.left - this.margin.right;
    this.height = this.outerHeight - this.margin.top - this.margin.bottom;
};

/** @private */
Rubix.prototype._setupCanvas = function() {
    this.elem.html('');
    this.canvas = d3.select(this.elem.get(0)).append('svg');
    this.canvas.attr('width', this.outerWidth);
    this.canvas.attr('height', this.outerHeight);

    this.canvas.append('desc').text("Powered by Rubix");

    this.defs = this.canvas.append('defs');

    this.defs.append('clipPath')
           .attr('id', this.master_id+'-clip')
         .append('rect')
           .attr('width', this.width)
           .attr('height', this.height+20)
           .attr('transform', 'translate(0, -20)');
};

/** @private */
Rubix.prototype._setupRoot = function() {
    this.root = this.canvas.append('g');
    this.root.attr('width', this.width);
    this.root.attr('height', this.height);
    if(!this.opts.hideAxisAndGrid) {
        this.root.attr('transform', 'translate('+this.margin.left+','+this.margin.top+')');
    } else {
        this.root.attr('transform', 'translate(0,'+this.margin.top+')');
    }

    if(!this.hasData) {
        this.canvas.append('text').attr('class', 'noData').attr('font-size', '30px').attr('text-anchor', 'middle').attr('transform', 'translate('+this.outerWidth/2+','+this.outerHeight/2+')').attr('font-family', 'Lato, "Lucida Grande", Arial, Helvetica, sans-serif').text("No Data");
    } else {
        this.canvas.select('.noData').style('display', 'none');
    }

    if(this.master_detail) {
        this.md_root = this.canvas.append('g');
        this.md_root.attr('width', this.width);
        this.md_root.attr('height', this.master_detail_height);
        if(!this.opts.hideAxisAndGrid) {
            this.md_root.attr('transform', 'translate('+this.margin.left+','+(this.margin.top+this.height+this.master_detail_margin_bottom)+')');
        }
        var rect = this.md_root.append('rect');
        rect.attr('width', this.md_root.attr('width'));
        rect.attr('height', this.md_root.attr('height'));
        rect.attr('fill', 'none');
        rect.attr('shape-rendering', 'crispEdges');
        this.md_root.append('g').attr('class', 'md-grid').attr('width', this.md_root.attr('width'));
        this.md_layers = this.md_root.append('g').attr('class', 'md-layers').attr('clip-path', 'url(#'+this.master_id+'-clip)');

        this.defs.append('clipPath')
                .attr('id', this.master_id+'-clip-symbols')
            .append('rect')
                .attr('width', this.md_root.attr('width'))
                .attr('height', this.height+20)
                .attr('transform', 'translate(0, -10)');

        this.defs.append('clipPath')
                .attr('id', this.master_id+'-clip-brush')
            .append('rect')
                .attr('width', this.md_root.attr('width')+20)
                .attr('height', this.master_detail_height)
                .attr('transform', 'translate(-10, 1)');
    }
};

Rubix.prototype.marginLeft = function(_left_) {
    if(!arguments.length) return this.margin.left;

    if(10 + _left_ + 10 < this.margin.defaultLeft) {
        this.margin.left = this.margin.defaultLeft;
    } else {
        this.margin.left = 10 + _left_ + 10;
    }
    if(this.axis.x.label.length) {
        if(this.opts.invertAxes) {
            this.margin.left += 15;
        }
    }
    if(this.axis.y.label.length) {
        if(!this.opts.invertAxes) {
            this.margin.left += 15;
        }
    }
    this.draw();
};

Rubix.prototype.forceRedraw = function() {
    try {
        this.hasData = false;
        for(var i in this.charts) {
            this.hasData = true;
            this.charts[i].forceRedraw(this);
        }
        if(this.hasData) {
            this.canvas.select('.noData').style('display', 'none');
            this.root.selectAll('.axis').style('display', null);
        } else {
            this.canvas.select('.noData').style('display', null);
            this.root.selectAll('.axis').style('display', 'none');
        }
    } catch(e) {
        // do nothing
    }
};

// flicked from crossfilter
Rubix.prototype.resizePath = function(d) {
    var e = +(d == 'e'),
        x = e ? 1 : -1,
        y = this.master_detail_height/2;
    return 'M' + (0.5 * x) + ',' + y
        + 'A6,6 0 0 ' + e + ' ' + (6.5 * x) + ',' + (y + 6)
        + 'V' + (2 * y - 6)
        + 'A6,6 0 0 ' + e + ' ' + (.5 * x) + ',' + (2 * y)
        + 'Z'
        + 'M' + (2.5 * x) + ',' + (y + 8)
        + 'V' + (2 * y - 8)
        + 'M' + (4.5 * x) + ',' + (y + 8)
        + 'V' + (2 * y - 8);
};

Rubix.prototype._createBrushPath = function() {
    var self = this;
    if(this.brush_path) this.brush_path.remove();
    this.brush_path = this.md_root.append('g');
    this.brush_path.attr('height', this.master_detail_height+5);
    this.brush_path.attr('width', this.md_root.attr('width'));
    this.brush_path.attr('class', 'x brush');
    this.brush_path.attr('transform', 'translate(0, -4)');
    // this.brush_path.attr('clip-path', 'url(#'+this.master_id+'-clip-brush)');
    this.brush_path.call(this.brush);

    var color = 'rgb(16, 16, 16)';
    if(this.opts.theme_style === 'light') {
        color = 'white';
    }

    this.brush_path.selectAll('.extent').attr('stroke', 'none').attr('fill-opacity', 0).attr('shape-rendering', 'crispEdges');
    this.brush_path.insert('rect', '.resize').attr('class', 'left-extent').attr('fill-opacity', 0.5).attr('fill', color).style('cursor', 'crosshair');
    this.brush_path.insert('rect', '.resize').attr('class', 'right-extent').attr('fill-opacity', 0.5).attr('fill', color).style('cursor', 'crosshair');
    this.brush_path.insert('rect', '.resize').attr('class', 'left-border').attr('x', 0).style('fill', this.opts.theme_style_color);
    this.brush_path.insert('rect', '.resize').attr('class', 'left-top-border').attr('x', 0).style('fill', this.opts.theme_style_color);
    this.brush_path.insert('rect', '.resize').attr('class', 'right-border').style('fill', this.opts.theme_style_color);
    this.brush_path.insert('rect', '.resize').attr('class', 'right-top-border').style('fill', this.opts.theme_style_color);
    this.brush_path.insert('rect', '.resize').attr('class', 'bottom-border').style('fill', 'rgb(16, 16, 16)').attr('fill-opacity', 0.3);

    var brush_x_pos = this.brush_path.select('.extent').attr('x');
    var brush_width = this.brush_path.select('.extent').attr('width');
    var brush_height = this.brush_path.select('.extent').attr('height');

    this.brush_path.select('.left-extent').attr('height', brush_height).attr('width', brush_x_pos).attr('x', 0);
    var brush_r_x_pos = parseFloat(brush_x_pos)+parseFloat(brush_width);
    this.brush_path.select('.right-extent').attr('height', brush_height).attr('x', brush_r_x_pos).attr('width', this.md_root.attr('width') - brush_r_x_pos);

    this.brush_path.select('.left-border').attr('width', 1).attr('x', brush_x_pos);
    this.brush_path.select('.left-top-border').attr('width', brush_x_pos);
    this.brush_path.select('.right-border').attr('width', 1).attr('x', brush_r_x_pos);
    this.brush_path.select('.right-top-border').attr('x', brush_r_x_pos).attr('width', this.md_root.attr('width') - brush_r_x_pos);
    this.brush_path.select('.bottom-border').attr('x', brush_x_pos).attr('width', brush_width).attr('transform', 'translate(0, ' + (this.master_detail_height+5) + ')');

    this.brush_path.selectAll('.resize').append('path').attr('d', function(d) {
        return self.resizePath(d);
    }).style('fill', '#EEE').style('stroke', '#666').attr('transform', 'translate(0, -'+this.master_detail_height/5+')');

    var inner_rect = this.brush_path.selectAll('rect');
    inner_rect.attr('height', this.master_detail_height+5);

    this.brush_path.select('.left-top-border').attr('height', 1);
    this.brush_path.select('.right-top-border').attr('height', 1);
    this.brush_path.select('.bottom-border').attr('height', 1);

    if(!this.extent.length) {
        this.extent = this.x2.domain();
        this.brush.extent(this.extent);
        this._brush();
    }
};

Rubix.prototype.bisectLeft = d3.bisector(function(d) { return d.x; }).left;
Rubix.prototype.bisectRight = d3.bisector(function(d) { return d.x; }).right;

Rubix.prototype.move_tooltip_x = function(dx, ys, points) {
    var dy, mid;
    if(ys.length) {
        dy = d3.mean(ys)/ys.length;
    }

    var copy = dx;
    if(this.axis.x.range === 'column' || this.axis.x.range === 'bar') {
        dx += this.x.rangeBand()/2;
    }

    var _elem = this.root_elem;

    var tooltipPadding = (this.tooltip.outerWidth() - this.tooltip.width())/2;

    var left = dx + this.margin.left + tooltipPadding;
    var top  = dy;

    var elem_far_right = _elem.width();

    var tooltip_far_right = left + this.tooltip.outerWidth();

    if(this.axis.x.range === 'column' || this.axis.x.range === 'bar') {
        dx = copy - this.x.rangeBand()/2;
    }

    if(tooltip_far_right > elem_far_right) {
        left -= (tooltip_far_right  - dx - this.margin.left + tooltipPadding);
    }

    this.tooltip.css('display', 'inline');
    this.tooltip.css('display', useTable);
    this.tooltip.css({
        'left': left,
        'top' : top
    });

    var html = "", formatterX, formatterY;
    if(this.tooltipFormatter.format.x.length) {
        if(this.axis.x.type === 'datetime') {
            formatterX = d3.time.format(this.tooltipFormatter.format.x);
        } else {
            formatterX = d3.format(this.tooltipFormatter.format.x);
        }
    } else {
        formatterX = function(d) { return d; };
    }
    if(this.tooltipFormatter.format.y.length) {
        if(this.axis.y.type === 'datetime') {
            formatterY = d3.time.format(this.tooltipFormatter.format.y);
        } else {
            formatterY = d3.format(this.tooltipFormatter.format.y);
        }
    } else {
        formatterY = function(d) { return d; };
    }
    for(var name in points) {
        var _x, _y;
        if(this.axis.x.type === 'datetime') {
            _x = formatterX(new Date(points[name].x));
        } else {
            if(points[name].invert) {
                _x = formatterX(this.x.invert(points[name].x));
            } else {
                _x = formatterX(points[name].x);
            }
        }
        if(this.axis.y.type === 'datetime') {
            _y = (points[name].y !== null) ? formatterY(new Date(points[name].y)) : null;
        } else {
            if(points[name].invert) {
                _y = (points[name].y !== null) ? formatterY(this.y.invert(points[name].y)) : null;
            } else {
                _y = formatterY(points[name].y);
            }
        }

        _x = this.tooltipFormatter.abs.x ? Math.abs(_x) : _x;
        _y = this.tooltipFormatter.abs.y ? Math.abs(_y) : _y;
        var series = "<div style='color: "+points[name].opts.color+"; margin-bottom: 2px; line-height: 22px;'><b style='position:relative; top: -5px; left: -2px;'><span style='font-size: 22px;'>■ </span><span style='position:relative; top: -3px; left: -2px;'>"+name+"</span></b></div>";
        var x = "<div style='font-size: 10px; margin-top: -10px;'>x : " + _x + " </div>";
        var y = "<div style='font-size: 10px; margin-top: -5px;'>y : " + _y + " </div><br>";
        html = (series+x+y) + html;
    }

    html = html.slice(0, html.length-4);

    this.tooltip.html(html);

    var tooltipHeight = this.tooltip.outerHeight();
    var total_height = tooltipHeight + dy;
    if(total_height >= _elem.height()) {
        top = this.margin.top;
        this.tooltip.css('top', top);
    }
};


Rubix.prototype.move_tooltip_y = function(dy, yx, points) {
    try {
    var dx, mid;
    if(yx.length) {
        dx = d3.max(yx);
    }

    var copy = dx;
    if(this.axis.x.range === 'column' || this.axis.x.range === 'bar') {
        dx += this.y.rangeBand()/2;
    }

    var _elem = $(this.canvas.node());

    var tooltipPadding = (this.tooltip.outerWidth() - this.tooltip.width())/2;

    var left = dx + this.margin.left + tooltipPadding;
    var top  = dy;

    var elem_far_right = _elem.width();

    var tooltip_far_right = left + this.tooltip.outerWidth();

    if(this.axis.x.range === 'column' || this.axis.x.range === 'bar') {
        dx = copy - this.y.rangeBand()/2;
    }

    if(tooltip_far_right > elem_far_right) {
        left -= tooltip_far_right  - dx - this.margin.left + tooltipPadding;
    }

    var elem_far_bottom = _elem.height();
    var tooltip_far_bottom = top + this.tooltip.outerHeight() + this.margin.bottom;

    if(tooltip_far_bottom > elem_far_bottom) {
        top -= (tooltip_far_bottom - elem_far_bottom);
    }

    this.tooltip.css('display', 'inline');
    this.tooltip.css('display', useTable);
    this.tooltip.css({
        'left': left,
        'top' : top
    });


    var html = "", formatterX, formatterY;
    if(this.tooltipFormatter.format.x.length) {
        if(this.axis.x.type === 'datetime') {
            formatterX = d3.time.format(this.tooltipFormatter.format.x);
        } else {
            formatterX = d3.format(this.tooltipFormatter.format.x);
        }
    } else {
        formatterX = function(d) { return d; };
    }
    if(this.tooltipFormatter.format.y.length) {
        if(this.axis.y.type === 'datetime') {
            formatterY = d3.time.format(this.tooltipFormatter.format.y);
        } else {
            formatterY = d3.format(this.tooltipFormatter.format.y);
        }
    } else {
        formatterY = function(d) { return d; };
    }
    for(var name in points) {
        var _x, _y;
        if(this.axis.y.type === 'datetime') {
            _x = formatterY(new Date(points[name].y));
        } else {
            if(points[name].invert) {
                _x = formatterY(this.y.invert(points[name].y));
            } else {
                _x = formatterY(points[name].y);
            }
        }
        if(this.axis.x.type === 'datetime') {
            _y = formatterX(new Date(points[name].x));
        } else {
            if(points[name].invert) {
                _y = formatterX(this.x.invert(points[name].x));
            } else {
                _y = formatterX(points[name].x);
            }
        }

        _x = this.tooltipFormatter.abs.x ? Math.abs(_x) : _x;
        _y = this.tooltipFormatter.abs.y ? Math.abs(_y) : _y;
        var series = "<div style='color: "+points[name].opts.color+"; margin-bottom: 2px'><b style='position:relative; top: -5px; left: -2px;'><span style='font-size: 22px;'>■ </span><span style='position:relative; top: -3px; left: -2px;'>"+name+"</span></b></div>";
        var x = "<div style='font-size: 10px; margin-top: -10px;'>x : " + _x + " </div>";
        var y = "<div style='font-size: 10px; margin-top: -5px;'>y : " + _y + " </div><br>";
        html = (series+x+y) + html;
    }

    html = html.slice(0, html.length-4);

    this.tooltip.html(html);
    } catch(e) {
        // do nothing
    }
};

Rubix.prototype.overlayX = function(self, coordinates) {
    var ycord = coordinates[1];
    try {
        if(self.axis.x.type === 'ordinal') {
            var x0 = coordinates[0];
            if(self.axis.x.range === 'column' || self.axis.x.range === 'bar') {
                x0 = x0 - self.x.rangeBand()/2;
            }

            var i  = self.bisectLeft(self.crosshair_data, x0, 1);
        } else {
            var x0 = self.x.invert(coordinates[0]+1);
            var i  = self.bisectLeft(self.crosshair_data, x0, 1);
        }

        var d0 = self.crosshair_data[i - 1],
            d1 = self.crosshair_data[i],
            d  = x0 - d0.x > d1.x - x0 ? d1 : d0;

        var y = d.y;
        var others = d.others;
        var xpos;
        var ys = [];
        var points = {};
        var ok = [];
        for(var name in self.charts) {
            try {
                if(y.hasOwnProperty(name)) {
                    if(self.axis.x.type === 'ordinal') {
                        if(y[name] !== null && d.others[name].y0 !== null) {
                            ok.push(true);
                            var dx = d.x;
                            var dy = self.y(y[name]);
                            if(self.axis.x.range === 'column' || self.axis.x.range === 'bar') {
                                if(self.grouped && self.charts[name].hasOwnProperty('count')) {
                                    dx = d.x + ((self.x.rangeBand()/(self.charts[name].layers.length)) * (self.charts[name].count)) + self.x.rangeBand()/(2*self.charts[name].layers.length);
                                } else {
                                    dx += self.x.rangeBand()/2;
                                }
                            }

                            self.charts[name].focus.attr('transform', 'translate(' + dx + ',' + dy +')').style('display', null);

                            if(self.axis.x.range === 'column' || self.axis.x.range === 'bar') {
                                if(self.grouped && self.charts[name].hasOwnProperty('count')) {
                                    xpos = d.x + self.x.rangeBand()/2;
                                } else {
                                    xpos = dx;
                                }
                            } else {
                                xpos = dx;
                            }
                            ys.push(dy);
                            points[name] = {
                                x: dx,
                                y: y[name] ? dy : null,
                                opts: self.charts[name].opts,
                                invert: true
                            };
                        }
                    } else {
                        ok.push(true);
                        var dx = self.x(d.x);
                        var dy = self.y(y[name]);
                        self.charts[name].focus.attr('transform', 'translate(' + dx + ',' + dy +')').style('display', null);

                        xpos = dx;
                        ys.push(dy);
                        points[name] = {
                            x: d.x,
                            y: y[name],
                            opts: self.charts[name].opts,
                            invert: false
                        };
                    }
                    if(ok.length) {
                        self.charts[name].on_focus(dx, dy);
                    }
                } else {
                    self.charts[name].focus.style('display', 'none');
                    self.charts[name].off_focus();
                }
            } catch(e) {
                // do nothing
            }
        }
        if(ok.length) {
            if(self.axis.x.type === 'ordinal') {
                var dx = d.x;
                if(self.axis.x.range === 'column' || self.axis.x.range === 'bar') {
                    dx += self.x.rangeBand()/2;
                }
                self.focusLine.style('display', null).attr('transform', 'translate(' + dx + ','+ '0)');
            } else {
                self.focusLine.style('display', null).attr('transform', 'translate(' + self.x(d.x) + ','+ '0)');
            }

            self.move_tooltip_x(xpos, ys, points);
        }
    } catch(e) {
        // do nothing
    }
};

Rubix.prototype.overlayY = function(self, coordinates) {
    // here .x is .y and .y should be .x due to axis inversion
    var xcord = coordinates[0];
    var y0 = coordinates[1];

    try {
        var len = 0;
        if(self.axis.y.type === 'ordinal') {
            if(self.axis.y.range === 'column' || self.axis.y.range === 'bar') {
                y0 = y0 - self.y.rangeBand()/2;
            }
        } else {
            y0 = self.y.invert(y0);
        }
        var i = self.bisectRight(self.crosshair_data, y0, 1);

        var d0 = self.crosshair_data[i - 1],
            d1 = self.crosshair_data[i],
            d  = y0 - d0.x > d1.x - y0 ? d1 : d0;

        var y = d.y;
        var others = d.others;
        var xpos;
        var ys = [];
        var points = {};
        var ok = [];
        for(var name in self.charts) {
            try {
                if(y.hasOwnProperty(name)) {
                    if(self.axis.y.type === 'ordinal') {
                        if(y[name] !== null && d.others[name].y0 !== null) {
                            ok.push(true);
                            var dx = d.x;
                            var dy = self.x(y[name]);
                            if(self.axis.y.range === 'column' || self.axis.y.range === 'bar') {
                                if(self.grouped && self.charts[name].hasOwnProperty('count')) {
                                    dx = d.x + ((self.y.rangeBand()/(self.charts[name].layers.length)) * (self.charts[name].count)) + self.y.rangeBand()/(2*self.charts[name].layers.length);
                                } else {
                                    dx += self.y.rangeBand()/2;
                                }
                            }

                            self.charts[name].focus.attr('transform', 'translate(' + dy + ',' + dx +')').style('display', null);
                            xpos = dx;
                            ys.push(dy);
                            points[name] = {
                                x: dy,
                                y: dx,
                                opts: self.charts[name].opts,
                                invert: true
                            };
                        }
                    } else {
                        ok.push(true);
                        var dx = self.y(d.x);
                        var dy = self.x(y[name]);
                        self.charts[name].focus.attr('transform', 'translate(' + dy + ',' + dx +')').style('display', null);
                        xpos = dx;
                        ys.push(dy);
                        points[name] = {
                            x: y[name],
                            y: d.x,
                            opts: self.charts[name].opts,
                            invert: false
                        };
                    }
                    if(ok.length) {
                        self.charts[name].on_focus(dy, dx);
                    }
                } else {
                    self.charts[name].focus.style('display', 'none');
                    self.charts[name].off_focus();
                }
            } catch(e) {
                // do nothing
            }
        }
        if(ok.length) {
            if(self.axis.y.type === 'ordinal') {
                var dx = d.x;
                if(self.axis.y.range === 'column' || self.axis.y.range === 'bar') {
                    dx += self.y.rangeBand()/2;
                }
                self.focusLine.style('display', null).attr('transform', 'translate(0,' + dx + ')');
            } else {
                self.focusLine.style('display', null).attr('transform', 'translate(0,'+self.y(d.x)+')');
            }

            self.move_tooltip_y(xpos, ys, points);
        }
    } catch(e) {
        // do nothing
    }
};

Rubix.prototype.resetFocus = function(forced) {
    if(!this.width) return;
    if(!this.height) return;

    if(this.focusLine) this.focusLine.remove();
    if(this.symbols) this.symbols.remove();
    if(this.overlay) this.overlay.remove();

    if(this.xlabel) this.xlabel.remove();
    if(this.ylabel) this.ylabel.remove();
    var self = this;

    if(this.axis.x.label.length) {
        this.xlabel = this.root.append('text');
        this.xlabel.style('font-size', '12px');
        this.xlabel.style('font-weight', 'bold');
        this.xlabel.style('font-family', 'Lato, "Lucida Grande", Arial, Helvetica, sans-serif');
        this.xlabel.attr('fill', this.xlabelcolor || 'steelblue');
        this.xlabel.attr('stroke', 'none');
        this.xlabel.style('text-anchor', 'middle');
        if(this.opts.invertAxes) {
            this.xlabel.attr('y', -this.margin.left+20);
            this.xlabel.attr('x', -parseInt(this.root.attr('height'))/2);
            this.xlabel.attr('transform', 'rotate(-90)');
            this.xlabel.text(this.axis.y.label);
        } else {
            this.xlabel.attr('y', (parseInt(this.root.attr('height')) + this.margin.bottom - 15));
            this.xlabel.attr('x', parseInt(this.root.attr('width'))/2);
            this.xlabel.text(this.axis.x.label);
        }
    }

    if(this.axis.y.label.length) {
        this.ylabel = this.root.append('text');
        this.ylabel.style('font-size', '12px');
        this.ylabel.style('font-weight', 'bold');
        this.ylabel.style('font-family', 'Lato, "Lucida Grande", Arial, Helvetica, sans-serif');
        this.ylabel.attr('fill', this.ylabelcolor || 'steelblue');
        this.ylabel.attr('stroke', 'none');
        this.ylabel.style('text-anchor', 'middle');
        if(this.opts.invertAxes) {
            this.ylabel.attr('y', (parseInt(this.root.attr('height')) + this.margin.bottom - 15));
            this.ylabel.attr('x', parseInt(this.root.attr('width'))/2);
            this.ylabel.text(this.axis.x.label);
        } else {
            this.ylabel.attr('y', -this.margin.left+20);
            this.ylabel.attr('x', -parseInt(this.root.attr('height'))/2);
            this.ylabel.attr('transform', 'rotate(-90)');
            this.ylabel.text(this.axis.y.label);
        }
    }

    if(this.opts.invertAxes) {
        this.focusLine = this.focus_line_group.append('line').attr('x1', 0).attr('y1', 0).attr('x2', this.width).attr('y2', 0).attr('stroke', this.opts.theme_focus_line_color).attr('stroke-width', 1).attr('shape-rendering', 'crispEdges').style('display', 'none').attr('class', 'focus-line');
    } else {
        this.focusLine = this.focus_line_group.append('line').attr('x1', 0).attr('y1', 0).attr('x2', 0).attr('y2', this.height).attr('stroke', this.opts.theme_focus_line_color).attr('stroke-width', 1).attr('shape-rendering', 'crispEdges').style('display', 'none').attr('class', 'focus-line');
    }

    this.symbols = this.symbols_group.append('g');
    for(var name in self.charts) {
        self.charts[name].on_complete_draw();
    }

    var order = [];
    for(var name in self.charts) {
        order.push(name);
    }

    if(this.axis.x.range == 'column' || this.axis.x.range == 'bar') {
        order.reverse();
    }

    for(var i=0; i<order.length; i++) {
        var name = order[i];
        self.charts[name].setupFocus();
    }

    if(this.master_detail) {
        if(!forced) {
            this._createBrushPath();
        }
    }

    this.overlay = this.root.append('rect');
    this.overlay.attr('class', 'overlay');
    this.overlay.attr('width', this.width);
    this.overlay.attr('height', this.height);
    this.overlay.attr('fill', 'none');
    this.overlay.attr('pointer-events', 'all');

    this.overlay.attr('clip-path', 'url(#'+this.master_id+'-clip)');

    var over = function() {
        $(window).trigger('rubix.sidebar.off');
        d3.event.preventDefault();
        self.focusLine.style('display', null);
        $(this).focus();
    };

    var move = function() {
        $(window).trigger('rubix.sidebar.off');
        d3.event.preventDefault();
        $(this).focus();
        if(!self.hasData) return;
        if(self.is_touch_device) {
            var coordinates = d3.touches(this)[0];
        } else {
            var coordinates = d3.mouse(this);
        }
        if(self.opts.invertAxes) {
            if(coordinates[1] < 0 || coordinates[1] > self.height) return;
            self.overlayY.call(this, self, coordinates);
        } else {
            if(coordinates[0] < 0 || coordinates[0] > self.width) return;
            self.overlayX.call(this, self, coordinates);
        }
    };

    var out = function() {
        $(window).trigger('rubix.sidebar.on');
        d3.event.preventDefault();
        $(this).focus();
        self.tooltip.hide();
        for(var name in self.charts) {
            try {
                self.charts[name].focus.style('display', 'none');
                self.charts[name].off_focus();
            } catch(e) {
                // do nothing
            }
        }
        self.focusLine.style('display', 'none');
    };

    if(self.is_touch_device) {
        if(window.navigator.msPointerEnabled) {
            this.overlay.on('MSPointerDown', over);
            this.overlay.on('MSPointerMove', move);
            this.overlay.on('MSPointerUp' , out);
        } else {
            this.overlay.on('touchstart', over);
            this.overlay.on('touchmove', move);
            this.overlay.on('touchend' , out);
        }
    } else {
        this.overlay.on('mouseover', over);
        this.overlay.on('mousemove', move);
        this.overlay.on('mouseout' , out);
    }
};

Rubix.prototype.runCommand = function(cmd) {
    for(var i in this.charts) {
        try {
            this.charts[i][cmd](this);
        } catch(e) {
            // do nothing
        }
    }
};

Rubix.prototype.resetLabelHandlers = function() {
    var self = this;
    $('.'+this.master_id+'-legend-labels').css({
        'opacity': 0.9
    });

    $('.'+this.master_id+'-legend-labels').off().on({
        'hover': function(e) {
            var hasClass = $(this).hasClass('toggle');

            if(hasClass) return;

            switch(e.type) {
                case 'mouseenter':
                    $(this).css({
                        'opacity': 1
                    });
                break;
                case 'mouseleave':
                    $(this).css({
                        'opacity': 0.9
                    });
                break;
                default:
                break;
            }
        },
        'click': function(e) {
            var hasClass = $(this).hasClass('toggle');
            var name = $(this).attr('data-name');
            var type = $(this).attr('data-type') || '';

            if(!hasClass) {
                $(this).css({
                    'opacity': 0.2
                });
                $(this).addClass('toggle');

                var chart = self.charts[name];
                chart.hidden();
                delete self.charts[name];
                self.chart_stack[name] = chart;

                if(type.length) {
                    var fetchData = self.column_stack_data;
                    var pushData = self.column_stack_data_stack;
                    if(type === 'astack') {
                        fetchData = self.area_stack_data;
                        pushData = self.area_stack_data_stack;
                    }
                    var rdata, orderKey = 0;
                    for(var i=0; i<fetchData.length; i++) {
                        if(!fetchData[i]) continue;
                        var fname = fetchData[i].key;
                        if(fname === name) {
                            orderKey = fetchData[i].orderKey;
                            rdata = fetchData[i];
                            fetchData.splice(i, 1);
                            break;
                        }
                    }

                    pushData.splice(orderKey, 0, rdata);
                }

                var data = self.data[name];
                self.data_stack[name] = data;
                delete self.data[name];
            } else {
                $(this).css({
                    'opacity': 1
                });
                $(this).removeClass('toggle');

                var chart = self.chart_stack[name];
                self.charts[name] = chart;
                delete self.chart_stack[name];

                if(type.length) {
                    var pushedData = self.column_stack_data_stack;
                    var origData = self.column_stack_data;
                    if(type === 'astack') {
                        pushedData = self.area_stack_data_stack;
                        origData = self.area_stack_data;
                    }
                    var rdata, orderKey = 0;
                    for(var i=0; i<pushedData.length; i++) {
                        if(!pushedData[i]) continue;
                        var fname = pushedData[i].key;
                        if(fname === name) {
                            orderKey = pushedData[i].orderKey;
                            rdata = pushedData[i];
                            pushedData.splice(i, 1);
                            break;
                        }
                    }

                    origData.splice(orderKey, 0, rdata);
                }

                var data = self.data_stack[name];
                self.data[name] = data;
                delete self.data_stack[name];
                self.charts[name].show();
            }

            /** TODO: Investigate why draw needs to be called twice */
            self.draw();
            self.draw();
        }
    });
};

/**
 * @param {?Object} opts
 * @return {Rubix.LineSeries}
 */
Rubix.prototype.line_series = function(opts) {
    opts = opts || {};
    opts.name  = opts.name || this._generate_name();
    if(this.charts.hasOwnProperty(opts.name)) {
        throw new Error("Series exists: " + name);
    }
    var line_series = new Rubix.LineSeries(this, opts);
    this.charts[line_series.name] = line_series;
    this.legend.append("<div class='"+this.master_id+"-legend-labels' data-name='"+line_series.name+"' style='cursor: pointer; display: inline-block; font-weight: bold; margin-right: 10px; color: "+line_series.opts.color+"'><span style='font-size: 22px;'>■ </span><span style='font-size: 12px; position:relative; top: -3px; left: -2px;'>"+line_series.name+"</span></div>");
    this.resetLabelHandlers();
    return line_series;
};

/**
 * @param {?Object} opts
 * @return {Rubix.AreaSeries}
 */
Rubix.prototype.area_series = function(opts) {
    opts = opts || {};
    opts.name  = opts.name || this._generate_name();
    if(this.charts.hasOwnProperty(opts.name)) {
        throw new Error("Series exists: " + name);
    }
    if(this.stacked) {
        var stacked_area_series = new Rubix.StackedAreaSeries(this, opts);
        this.charts[stacked_area_series.name] = stacked_area_series;
        this.legend.append("<div class='"+this.master_id+"-legend-labels' data-name='"+stacked_area_series.name+"' data-type='astack' style='cursor: pointer; display: inline-block; font-weight: bold; margin-right: 10px; color: "+stacked_area_series.opts.color+"'><span style='font-size: 22px;'>■ </span><span style='font-size: 12px; position:relative; top: -3px; left: -2px;'>"+stacked_area_series.name+"</span></div>");
        this.resetLabelHandlers();
        return stacked_area_series;
    }
    var area_series = new Rubix.AreaSeries(this, opts);
    this.charts[area_series.name] = area_series;
    this.legend.append("<div class='"+this.master_id+"-legend-labels' data-name='"+area_series.name+"' style='cursor: pointer; display: inline-block; font-weight: bold; margin-right: 10px; color: "+area_series.opts.color+"'><span style='font-size: 22px;'>■ </span><span style='font-size: 12px; position:relative; top: -3px; left: -2px;'>"+area_series.name+"</span></div>");
    this.resetLabelHandlers();
    return area_series;
};

Rubix.prototype._generate_name = function() {
    return 'Series ' + (++this.chart_counter);
};

/**
 * @param {?Object} opts
 * @return {Rubix.ColumnSeries}
 */
Rubix.prototype.column_series = function(opts) {
    opts = opts || {};
    opts.name  = opts.name || this._generate_name();
    if(this.charts.hasOwnProperty(opts.name)) {
        throw new Error("Series exists: " + name);
    }
    if(this.opts.invertAxes !== false || this.opts.axis.x.range !== 'column') {
        this.opts.invertAxes = false;
        this.opts.axis.x.type = 'ordinal';
        this.opts.axis.x.range = 'column';
        this.setup()
    }
    var column_series = new Rubix.ColumnSeries(this, opts);
    this.charts[column_series.name] = column_series;
    this.legend.append("<div class='"+this.master_id+"-legend-labels' data-name='"+column_series.name+"' data-type='cstack' style='cursor: pointer; display: inline-block; font-weight: bold; margin-right: 10px; color: "+column_series.opts.color+"'><span style='font-size: 22px;'>■ </span><span style='font-size: 12px; position:relative; top: -3px; left: -2px;'>"+column_series.name+"</span></div>");
    this.resetLabelHandlers();
    return column_series;
};

/**
 * @param {?Object} opts
 * @return {Rubix.ColumnSeries}
 */
Rubix.prototype.bar_series = function(opts) {
    opts = opts || {};
    opts.name  = opts.name || this._generate_name();
    if(this.charts.hasOwnProperty(opts.name)) {
        throw new Error("Series exists: " + name);
    }
    if(this.opts.invertAxes !== true || this.opts.axis.x.range !== 'column') {
        this.opts.invertAxes = true;
        this.opts.axis.x.type = 'ordinal';
        this.opts.axis.x.range = 'column';
        this.setup()
    }
    var column_series = new Rubix.ColumnSeries(this, opts);
    this.charts[column_series.name] = column_series;
    this.legend.append("<div class='"+this.master_id+"-legend-labels' data-name='"+column_series.name+"' data-type='cstack' style='cursor: pointer; display: inline-block; font-weight: bold; margin-right: 10px; color: "+column_series.opts.color+"'><span style='font-size: 22px;'>■ </span><span style='font-size: 12px; position:relative; top: -3px; left: -2px;'>"+column_series.name+"</span></div>");
    this.resetLabelHandlers();
    return column_series;
};

window.Rubix = window.Rubix || {};
/**
 * @param {Rubix} rubix
 * @param {Object} opts
 * @constructor
 */
Rubix.StackedAreaSeries = function(rubix, opts) {
    this.opts = opts;
    this.opts.color = this.opts.color || 'steelblue';
    this.opts.marker = this.opts.marker || 'circle';
    this.opts.fillopacity = this.opts.fillopacity || 0.5;
    this.opts.strokewidth = this.opts.strokewidth || 1;
    this.opts.noshadow = this.opts.noshadow || false;
    this.show_markers = this.opts.show_markers;

    if(!this.opts.hasOwnProperty('name')) throw new Error('StackedAreaSeries should have a \'name\' property');

    this.name = this.opts.name;

    this.chart_hidden = false;
    this.temp_stack = [];

    this.setup = false;
    this.last_stack = [];
    this.last_stack_name = '';
    this.last_stack_added = false;

    this._setup(rubix);
};

/**
 * @param {Rubix} rubix
 * @private
 */
Rubix.StackedAreaSeries.prototype._setup = function(rubix) {
    this.rubix = rubix;

    this.root   = this.rubix.root;
    this.data   = this.rubix.data;
    this.width  = this.rubix.width;
    this.height = this.rubix.height;
    this.stack  = this.rubix.area_stack;
    this.offset = this.rubix.area_offset;
    this.area_stack_data = this.rubix.area_stack_data;
    this.stacked_area_series = this.rubix.root_stacked_area_series;
    this.show_markers = (this.show_markers === undefined) ? this.rubix.show_markers : this.show_markers;

    this.master_detail = this.rubix.master_detail;

    if(this.master_detail) {
        this.md_root = this.rubix.md_root;
    }

    if(!this.id) {
        this.id = this.rubix.uid('stacked-area');
    }

    /** separator */
    var self = this;
    this.line = d3.svg.line();
    this.line.defined(function(d) {
        if(self.rubix.offset === 'expand') {
            if(d.y_new === 0 && d.y0 === 1) {
                return false;
            }
            return true;
        }

        return d.y_new !== null && d.x !== null;
    });
    if(this.rubix.opts.invertAxes) {
        this.line.x(function(d) {
            var val = d.y0 + d.y_new;
            if(isNaN(val)) {
                return 0;
            }
            return self.rubix.x(val);
        });
        this.line.y(function(d) {
            if(self.rubix.axis.y.range === 'column' || self.rubix.axis.y.range === 'bar') {
                return self.rubix.y(d.x) + self.rubix.y.rangeBand()/2;
            }
            return self.rubix.y(d.x);
        });
        this.line.interpolate(this.rubix.interpolate);
    } else {
        this.line.x(function(d) {
            if(self.rubix.axis.x.range === 'column' || self.rubix.axis.x.range === 'bar') {
                return self.rubix.x(d.x) + self.rubix.x.rangeBand()/2;
            }
            return self.rubix.x(d.x);
        });
        this.line.y(function(d) {
            var val = d.y0 + d.y_new;
            if(isNaN(val)) {
                return 0;
            }
            return self.rubix.y(val);
        });
        this.line.interpolate(this.rubix.interpolate);
    }

    this.area = d3.svg.area();
    this.area.defined(this.line.defined());

    if(this.rubix.opts.invertAxes) {
        this.area.x0(function(d) {
            var val = d.y0;
            if(isNaN(val)) {
                return 0;
            }
            return self.rubix.x(val);
        });
        this.area.x1(function(d) {
            var val = d.y0 + d.y_new;
            if(isNaN(val)) {
                return 0;
            }
            return self.rubix.x(val);
        });
        this.area.y(function(d) {
            if(self.rubix.axis.y.range === 'column' || self.rubix.axis.y.range === 'bar') {
                return self.rubix.y(d.x) + self.rubix.y.rangeBand()/2;
            }
            return self.rubix.y(d.x);
        });
        this.area.interpolate(this.rubix.interpolate);
    } else {
        this.area.x(function(d) {
            if(self.rubix.axis.x.range === 'column' || self.rubix.axis.x.range === 'bar') {
                return self.rubix.x(d.x) + self.rubix.x.rangeBand()/2;
            }
            return self.rubix.x(d.x);
        });
        this.area.y0(function(d) {
            var val = d.y0;
            if(isNaN(val)) {
                return 0;
            }
            return self.rubix.y(val);
        });
        this.area.y1(function(d) {
            var val = d.y0 + d.y_new;
            if(isNaN(val)) {
                return 0;
            }
            return self.rubix.y(val);
        });
        this.area.interpolate(this.rubix.interpolate);
    }

    if(this.master_detail) {
        this.master_line = d3.svg.line();
        this.master_line.defined(function(d) {
            if(self.rubix.area_offset === 'expand') {
                if(d.y_new === 0 && d.y0 === 1) {
                    return false;
                }
                return true;
            }

            return d.y_new !== null && d.x !== null;
        });
        this.master_line.x(function(d) {
            return self.rubix.x2(d.x);
        });
        this.master_line.y(function(d) {
            var val = d.y0 + d.y_new;
            if(isNaN(val)) {
                return 0;
            }
            return self.rubix.y2(val);
        });
        this.master_line.interpolate(this.rubix.interpolate);

        this.master_area = d3.svg.area();
        this.master_area.defined(this.master_line.defined());
        this.master_area.x(function(d) {
            return self.rubix.x2(d.x);
        });
        this.master_area.y0(function(d) {
            var val = d.y0;
            if(isNaN(val)) {
                return 0;
            }
            return self.rubix.y2(val);
        });
        this.master_area.y1(function(d) {
            var val = d.y0 + d.y_new;
            if(isNaN(val)) {
                return 0;
            }
            return self.rubix.y2(val);
        });
        this.master_area.interpolate(this.rubix.interpolate);
    }
};

// Alias for draw
Rubix.StackedAreaSeries.prototype.redraw = function(rubix) {
    this._setup(rubix);
    this.draw();
};

Rubix.StackedAreaSeries.prototype.noRedraw = function(rubix) {
    this._setup(rubix);
    this.draw(true);
};

/**
 * @param {Array|Object} data
 */
Rubix.StackedAreaSeries.prototype.addData = function(data) {
    this.rubix.data_changed = true;
    if(!(data instanceof Array)) {
        if(!(data instanceof Object)) {
            throw new Error("Data must be an array or object");
        } else {
            if(!(data.hasOwnProperty('x') || data.hasOwnProperty('y'))) {
                throw new Error("Object must be in the form: {x: ..., y: ...}");
            }

            data = [data];
        }
    }

    if(!data.length) return;

    if(!this.rubix.opts.noSort) {
        data.sort(function(a, b) {
            if(a.x > b.x) {
                return 1;
            } else if(a.x === b.x) {
                return 0;
            } else {
                return -1;
            }
        });
    }

    this.data[this.name] = data;

    this.area_stack_data.push({
        key: this.name,
        color: this.opts.color,
        values: this.data[this.name],
        fillopacity: this.opts.fillopacity,
        strokewidth: this.opts.strokewidth,
        orderKey: this.area_stack_data.length
    });

    this.rubix.resetAxis(true);
    this.rubix.forceRedraw();
    this._animate_draw();
};

Rubix.StackedAreaSeries.prototype.draw = function(forced) {
    if(!this.name) return;
    if(!this.data) return;
    if(!this.data.hasOwnProperty(this.name)) return;
    if(!this.data[this.name].length) return;

    var oldLayers = this.layers;
    try {
        this.layers = this.stack(this.area_stack_data);
    } catch(e) {
        // data un-available. retaining old layer.
        this.layers = oldLayers;
    }

    var self = this;

    var isConstructed = this.stacked_area_series.selectAll('.'+this.id)[0].length;

    if(!isConstructed) {
        try {
            this.stacked_area_series.selectAll('.layer').remove();
            this.stacked_area_series.selectAll('.' + this.id + '-line').remove();

            var p = this.stacked_area_series.selectAll('.layer').data(this.layers, function(d) {
                return d.key;
            });

            var createdPath = p.enter().append('path');
            createdPath.attr('class', 'layer')
                        .attr('d', function(d) { return self.area(d.values); })
                        .attr('fill', function(d) { return d.color; })
                        .attr('fill-opacity', function(d) {
                            return d.fillopacity
                        })
                        .attr('stroke', 'none');

            p.exit().remove();

            if(this.master_detail) {
                createdPath.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip)');
                createdPath.classed('clipped', true);

                if(!forced) {
                    var md_p = this.md_root.select('.md-layers > .md_stacked_area_series').selectAll('.layer').data(this.layers, function(d) {
                        return d.key;
                    });

                    var createdMdPath = md_p.enter().append('path');
                    createdMdPath.attr('class', 'layer')
                                 .attr('d', function(d) { return self.master_area(d.values); })
                                 .attr('fill', function(d) { return d.color; })
                                 .attr('fill-opacity', function(d) {
                                    return d.fillopacity;
                                 })
                                 .attr('stroke', 'none');

                    createdMdPath.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip)');

                    createdMdPath.classed('clipped', true);

                    md_p.exit().remove();
                }
            }

            for(var i=0; i<this.layers.length; i++) {
                var name = this.layers[i].key;
                if(this.name === name) {
                    var datum = this.layers[i].values;
                    if(!this.opts.noshadow) {
                        this.strokePath1 = this.stacked_area_series.append('path').attr('class', this.id+'-line').datum(datum).attr('d', this.line).attr('stroke', 'black').attr('fill', 'none').attr('stroke-linecap', 'round').attr('stroke-width', 5 * this.opts.strokewidth).attr('stroke-opacity', 0.05000000000000001).attr('transform', 'translate(1,1)');
                        this.strokePath2 = this.stacked_area_series.append('path').attr('class', this.id+'-line').datum(datum).attr('d', this.line).attr('stroke', 'black').attr('fill', 'none').attr('stroke-linecap', 'round').attr('stroke-width', 3 * this.opts.strokewidth).attr('stroke-opacity', 0.1).attr('transform', 'translate(1,1)');
                        this.strokePath3 = this.stacked_area_series.append('path').attr('class', this.id+'-line').datum(datum).attr('d', this.line).attr('stroke', 'black').attr('fill', 'none').attr('stroke-linecap', 'round').attr('stroke-width', 1 * this.opts.strokewidth).attr('stroke-opacity', 0.15000000000000002).attr('transform', 'translate(1,1)');
                    }
                    this.linePath = this.stacked_area_series.append('path').datum(datum).attr('class', this.id+'-line').attr('stroke', this.opts.color).attr('fill', 'none').attr('stroke-linecap', 'round').attr('d', this.line).attr('stroke-width', 2 * this.opts.strokewidth);

                    if(this.master_detail) {
                        if(!forced) {
                            this.md_root.select('.md-layers > .md_stacked_area_series').selectAll('.' + this.id + '-line').remove();
                            this.masterLinePath = this.md_root.select('.md-layers > .md_stacked_area_series').append('path').datum(datum).attr('class', this.id+'-line').attr('stroke', this.opts.color).attr('fill', 'none').attr('stroke-linecap', 'round').attr('d', this.master_line).attr('stroke-width', 2 * this.opts.strokewidth);
                            this.masterLinePath.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip)');
                            this.masterLinePath.classed('clipped', true);

                            if(this.dataChanged) {
                                this.rubix.resetExtent();
                                this.dataChanged = false;
                            }
                        }

                        if(!this.opts.noshadow) {
                            this.strokePath1.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip)');
                            this.strokePath2.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip)');
                            this.strokePath3.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip)');
                        }
                        this.linePath.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip)');

                        if(!this.opts.noshadow) {
                            this.strokePath1.classed('clipped', true);
                            this.strokePath2.classed('clipped', true);
                            this.strokePath3.classed('clipped', true);
                        }
                        this.linePath.classed('clipped', true);
                    }
                    break;
                }
            }
        } catch(e) {
            // do nothing
        }
        this.setup = true;
    } else {
        this.rubix.runCommand('globalRedraw');
    }

    this.rubix.resetFocus(forced);
};

/**
 * @param {Object} data
 * @param {?Boolean} shift
 * @param {?Boolean} noRedraw
 */
Rubix.StackedAreaSeries.prototype.updatePoint = function(data, shift, noRedraw) {
    this.addPoint(data, shift, noRedraw);
};

/**
 * @param {*} ref
 * @param {?Boolean} noRedraw
 */
Rubix.StackedAreaSeries.prototype.removePoint = function(ref, noRedraw) {
    if(this.chart_hidden) {
        this.temp_stack.push({
            type: 'removePoint',
            ref: ref
        });
        return;
    }

    if(!this.data[this.name]) {
        this.data[this.name] = [];
    }

    var removed = false, pos = 0;
    for(var i=0; i<this.data[this.name].length; i++) {
        if(this.data[this.name][i].x === ref) {
            this.data[this.name][i].y = null;
            pos = i;
            removed = true;
            break;
        }
    }

    if(!removed) return;

    var found = false;
    for(var i=0; i<this.area_stack_data.length; i++) {
        var st = this.area_stack_data[i];
        try {
            if(st.values[pos].x === ref) {
                if(st.values[pos].y !== null) {
                    found = true;
                    break;
                }
            }
        } catch(e) {
            // do nothing
        }
    }

    if(!found) {
        for(var i=0; i<this.area_stack_data.length; i++) {
            this.area_stack_data[i].values.splice(pos, 1);
        }
    }

    var oldLayers = this.layers;
    try {
        this.layers = this.stack(this.area_stack_data);
    } catch(e) {
        // data un-available. retaining old layer.
        this.layers = oldLayers;
    }

    if(this.master_detail) {
        this.dataChanged = true;
    }

    if(noRedraw) return;

    if(this.setup) {
        this._animate_draw();
    } else {
        this.rubix.resetAxis(true);
        this.rubix.forceRedraw();
    }
};

/**
 * @param {Object} data
 * @param {?Boolean} shift
 * @param {?Boolean} noRedraw
 */
Rubix.StackedAreaSeries.prototype.addPoint = function(data, shift, noRedraw) {
    this.rubix.data_changed = true;
    if(!(data instanceof Object) || (data instanceof Array)) {
        throw new Error("Object required for addPoint");
    }
    if(!(data.hasOwnProperty('x') || data.hasOwnProperty('y'))) {
        throw new Error("Object must be in the form: {x: ..., y: ...}");
    }

    if(this.chart_hidden) {
        this.temp_stack.push({
            type: 'addPoint',
            data: data,
            shift: shift
        });
        return;
    }

    if(!this.data[this.name]) {
        this.data[this.name] = [];
    }

    var added = false;
    for(var i=0; i<this.data[this.name].length; i++) {
        if(this.data[this.name][i].x === data.x) {
            this.data[this.name][i].y = data.y;
            added = true;
            break;
        }
    }

    if(!added) {
        this.data[this.name].push(data);
    }

    this.data[this.name].sort(function(a, b) {
        if(a.x > b.x) {
            return 1;
        } else if(a.x === b.x) {
            return 0;
        } else {
            return -1;
        }
    });

    if(this.rubix.opts.interval) {
        if(this.rubix.opts.interval < this.data[this.name].length) {
            this.data[this.name].shift();
        }
    } else {
        if(shift) {
            this.data[this.name].shift();
        }
    }

    var max_elems = d3.max(this.area_stack_data, function(d) {
        return d.values.length;
    });

    for(var i=0; i<this.area_stack_data.length; i++) {
        var st = this.area_stack_data[i];
        if(st.values.length < max_elems) {
            st.values.push({
                x: data.x,
                y: null
            });
        }
    }

    var oldLayers = this.layers;
    try {
        this.layers = this.stack(this.area_stack_data);
    } catch(e) {
        // data un-available. retaining old layer.
        this.layers = oldLayers;
    }

    if(this.master_detail) {
        this.dataChanged = true;
    }

    if(noRedraw) return;

    if(this.setup) {
        this._animate_draw();
    } else {
        this.rubix.resetAxis(true);
        this.rubix.forceRedraw();
    }
};

Rubix.StackedAreaSeries.prototype._animate_draw = function() {
    var text = this.root.selectAll('.y.axis').selectAll('text')[0];
    var width = [];
    for(var i=0; i<text.length; i++) {
        width.push(text[i].getBBox().width);
    }
    var origMaxWidth = d3.max(width);

    this.rubix.resetAxis(true);

    this.rubix.runCommand('globalRedraw');

    text = this.root.selectAll('.y.axis').selectAll('text')[0];
    width = [];
    for(var i=0; i<text.length; i++) {
        width.push(text[i].getBBox().width);
    }

    var maxWidth = d3.max(width);

    this.rubix.marginLeft(maxWidth);
    this.rubix.resetFocus();
};

Rubix.StackedAreaSeries.prototype.hidden = function() {
    this.chart_hidden = true;
};

Rubix.StackedAreaSeries.prototype.show = function() {
    this.chart_hidden = false;
    while(this.temp_stack.length>1) {
        var rawData = this.temp_stack.shift();
        if(rawData.type === 'addPoint') {
            this.addPoint(rawData.data, rawData.shift, true);
        } else {
            this.removePoint(rawData.ref);
        }
    }
    if(this.temp_stack.length) {
        var rawData = this.temp_stack.shift();
        if(rawData.type === 'addPoint') {
            this.addPoint(rawData.data, rawData.shift, true);
        } else {
            this.removePoint(rawData.ref);
        }
    }
};

Rubix.StackedAreaSeries.prototype.globalRedraw = function(rubix) {
    this.stacked_area_series.select('.'+this.id+'-line').attr('d', this.line);
};

Rubix.StackedAreaSeries.prototype.forceRedraw = function(rubix) {
    this.redraw(rubix);
};

Rubix.StackedAreaSeries.prototype.on_complete_draw = function() {
    if(!this.setup) return;
    var self = this;
    if(this.opts.marker !== undefined && this.show_markers) {
        if(this.master_detail) {
            this.rubix.symbols.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip-symbols)');
        }
        this.markers = this.rubix.symbols.selectAll('.'+this.id+'-marker');
        switch(this.opts.marker) {
            case 'circle':
            case 'cross':
            case 'square':
            case 'diamond':
            case 'triangle-up':
            case 'triangle-down':
                var symbol = d3.svg.symbol();
                symbol.type(this.opts.marker);

                var symbolType = this.markers.data(this.data[this.name]);
                var symbolPath = symbolType.enter().append('path');
                symbolPath.attr('d', symbol)
                      .attr('class', this.id + '-marker')
                      .attr('fill', this.opts.color)
                      .style('display', function(d) {
                            if(self.rubix.area_offset === 'expand') {
                                if(d.y_new === 0 && d.y0 === 1) {
                                    return 'none';
                                }
                                return null;
                            }
                            if(d.y_new === null) {
                                return 'none';
                            }
                            return null;
                      })
                      .attr('stroke', 'white')
                      .attr('transform', function(d) {
                            var val = d.y0 + d.y_new;
                            if(isNaN(val)) {
                                val = 0;
                            }
                            if(self.rubix.opts.invertAxes) {
                                var _y = self.rubix.y(d.x);
                                var _x = self.rubix.x(val);
                                if(self.rubix.axis.y.range === 'column' || self.rubix.axis.y.range === 'bar') {
                                    _y += self.rubix.y.rangeBand()/2;
                                }
                            } else {
                                var _y = self.rubix.y(val);
                                var _x = self.rubix.x(d.x);
                                if(self.rubix.axis.x.range === 'column' || self.rubix.axis.x.range === 'bar') {
                                    _x += self.rubix.x.rangeBand()/2;
                                }
                            }
                            return 'translate('+_x+','+_y+')';
                      });
                symbolType.exit().remove();
            break;
            default:
                throw new Error('Unknown marker type : ' + this.opts.marker);
            break;
        }
    }
};

Rubix.StackedAreaSeries.prototype.setupFocus = function() {
    if(!this.setup) return;
    if(this.focus) this.focus.remove();
    this.focus = this.rubix.focus_group.append('g');
    this.focus.attr('class', 'focus');
    this.focus.style('display', 'none');
    switch(this.opts.marker) {
        case 'circle':
        case 'cross':
        case 'square':
        case 'diamond':
        case 'triangle-up':
        case 'triangle-down':
            var symbol = d3.svg.symbol();
            symbol.type(this.opts.marker);
            var path = this.focus.append('path').attr('d', symbol).attr('fill', this.opts.color).attr('stroke', 'white').attr('stroke-width', 2);
        break;
        default:
            throw new Error('Unknown marker type : ' + this.opts.marker);
        break;
    }
};

Rubix.StackedAreaSeries.prototype.on_focus = function() {
    if(!this.setup) return;
    this.linePath.attr('stroke-width', 2 * this.opts.strokewidth);
};

Rubix.StackedAreaSeries.prototype.off_focus = function() {
    if(!this.setup) return;
    this.linePath.attr('stroke-width', 2 * this.opts.strokewidth);
};
window.Rubix = window.Rubix || {};

var slugify = function(text) {
    text = text.replace(/[^-a-zA-Z0-9,&\s]+/ig, '');
    text = text.replace(/-/gi, "_");
    text = text.replace(/\s/gi, "-");
    return text;
};

/**
 * @param {string} id
 * @param {Object} opts
 * @return {Rubix.PieDonut}
 */
Rubix.Pie = function(id, opts) {
    return new Rubix.PieDonut(id, 'pie', opts);
};

/**
 * @param {string} id
 * @param {Object} opts
 * @return {Rubix.PieDonut}
 */
Rubix.Donut = function(id, opts) {
    return new Rubix.PieDonut(id, 'donut', opts);
};

/**
 * @private
 * @param {string} id
 * @param {Rubix.Pie || Rubix.Donut} type
 * @param {Object} opts
 * @constructor
 */
Rubix.PieDonut = function(id, type, opts) {
    opts = opts || {};
    this.type = type;
    this.is_touch_device = 'ontouchstart' in document.documentElement;

    this.legend_hidden = {};

    this.chart_counter = 0;
    this.master_id = this.uid('master_id');

    this.area_stack_data_stack = [];
    this.column_stack_data_stack = [];

    this.root_elem = $(id);
    this.root_elem.css('position', 'relative');
    this.root_elem.addClass('rubixccnium-main-chart');
    this.root_elem.append('<div class="rubixccnium-tooltip"></div>');
    this.root_elem.append('<div class="rubixccnium-title"></div>');
    this.root_elem.append('<div class="rubixccnium-subtitle"></div>');
    this.root_elem.append('<div class="rubixccnium-chart"><div style="margin-top:5px">Loading...</div></div>');
    this.root_elem.append('<div class="rubixccnium-legend"></div>');

    var width = opts.width || '100%';
    var height = opts.height || 150;
    this.elem_width = width, this.elem_height = height;
    this.root_elem.width(width).height(height);

    this.elem = this.root_elem.find('.rubixccnium-chart');

    this.tooltip = this.root_elem.find('.rubixccnium-tooltip');
    this.tooltip.hide();
    this.tooltip.html("");

    opts.tooltip = opts.tooltip || {};

    this.tooltip.css({
        'font-family': 'Lato, "Lucida Grande", Arial, Helvetica, sans-serif',
        'font-size': '12px',
        'position': 'absolute',
        'background': 'white',
        'color': '#89949B',
        'display': 'none',
        'padding': '5px 15px',
        'pointer-events': 'none',
        'border-radius': '5px',
        'z-index': 100,
        'min-height': 50,
        'user-select': 'none',
        'cursor': 'default',
        'border': '3px solid ' + (opts.tooltip.color ? opts.tooltip.color : '#89949B'),
        'box-shadow': 'rgba(0, 0, 0, 0.2) 2px 4px 8px',
        'background': 'white'
    });

    opts.legend = opts.legend || {};

    this.legend = this.root_elem.find('.rubixccnium-legend');
    this.legend.css({
        'font-family': "Lato, 'Lucida Grande', Arial, Helvetica, sans-serif",
        'text-align': 'center',
        'margin-top': opts.legend.top || '-10px',
        'margin-bottom': opts.legend.bottom || '5px',
        'user-select': 'none',
        'display': opts.hideLegend ? 'none' : 'block'
    });

    this.title = this.root_elem.find('.rubixccnium-title');
    this.subtitle = this.root_elem.find('.rubixccnium-subtitle');

    this.title.css({
        'font-family': "Lato, 'Lucida Grande', Arial, Helvetica, sans-serif",
        'text-align': 'center',
        'user-select': 'none',
        'font-weight': 'bold',
        'font-size': '16px',
        'color': 'steelblue',
        'margin-top': '10px',
        'cursor': 'default'
    });

    this.subtitle.css({
        'font-family': "Lato, 'Lucida Grande', Arial, Helvetica, sans-serif",
        'text-align': 'center',
        'user-select': 'none',
        'font-size': '10px',
        'color': 'steelblue',
        'margin-top': '10px',
        'cursor': 'default',
        'opacity': 0.8
    });

    var self = this;

    this.elem.css({
        'width': '100%',
        'height': parseInt(this.root_elem.get(0).style.height),
        'user-select': 'none',
        'cursor': 'default'
    });
    this.root_elem.css('height', '100%');
    this.opts = opts || {};

    this.data = [];
    this.is_touch_device = 'ontouchstart' in document.documentElement;
    this.d3_eventSource = function () {
        var e = d3.event, s;
        while (s = e.sourceEvent) e = s;
        return e;
    }

    this.last_render = null;
    this.data_changed = false;
    this.setup();
};

Rubix.PieDonut.prototype.setup = function() {
    this._setupOpts();
    this._setupOnce();
    this._setupRedraw();

    this.draw();
};


/** @private */
Rubix.PieDonut.prototype._setupOpts = function() {
    this.opts.theme_style = this.opts.theme_style || 'light';
    this.opts.theme_style_color = (this.opts.theme_style === 'light') ? '#C0D0E0' : '#555';
    this.opts.theme_focus_line_color = (this.opts.theme_style === 'light') ? '#C0D0E0' : '#888';

    this.opts.legend_color_brightness = this.opts.legend_color_brightness || 0.5;
    this.opts.global_legend_color = this.opts.global_legend_color || false;

    this.opts.titleColor = this.opts.titleColor || 'steelblue';
    this.opts.subtitleColor = this.opts.subtitleColor || 'steelblue';

    this.title.css('color', this.opts.titleColor);
    this.subtitle.css('color', this.opts.subtitleColor);

    if(this.opts.theme_style === 'dark') {
        this.tooltip.css({
            "color": "#aaa",
            "font-weight": "bold",
            "border": "1px solid #222",
            "background-color": "#303030"
        });
    }

    this.opts.margin = this.opts.margin || {};
    this.margin = {
        top    : this.opts.margin.top    || 25,
        left   : this.opts.margin.left   || 25,
        right  : this.opts.margin.right  || 25,
        bottom : this.opts.margin.bottom || 25
    };

    this.opts.tooltip = this.opts.tooltip || {};
    this.opts.tooltip.format = this.opts.tooltip.format || {};
    this.tooltipFormatter = {
        format: {
            x: this.opts.tooltip.format.x || '',
            y: this.opts.tooltip.format.y || ''
        }
    }

    this.resize = this.opts.resize || 'throttled';

    this.opts.title = this.opts.title || '';
    this.opts.subtitle = this.opts.subtitle || '';

    this.title.html(this.opts.title);
    this.subtitle.html(this.opts.subtitle);
    if(this.opts.title.length || this.opts.subtitle.length) {
        this.elem.css('margin-top', '-20px');
    }
    (this.opts.title.length) ? this.title.show() : this.title.hide();
    (this.opts.subtitle.length) ? this.subtitle.show() : this.subtitle.hide();
};

/** @private */
Rubix.PieDonut.prototype._setupOnce = function() {
    // Add Pie Stack data here.
};

Rubix.PieDonut.prototype._setupRedraw = function() {
    var self = this;

    $(window).on('orientationchange', function() {
        self.draw();
    });

    $(window).on('rubix.redraw.'+this.master_id, function() {
        self.draw();
    });

    switch(this.resize) {
        case 'always':
            $(window).on('resize', function() {
                self.draw();
            });
        break;
        case 'debounced':
            $(window).on('debouncedresize', function() {
                self.draw();
            });
        break;
        case 'throttled':
            $(window).on('throttledresize', function() {
                self.draw();
            });
        break;
        default:
            throw new Error('Unknown resize type!');
        break;
    }
};

Rubix.PieDonut.prototype.draw = function() {
    this._draw();
};

/**
 * @private
 */
Rubix.PieDonut.prototype._draw = function() {
    this._cleanElement();
    this._setSize();
    this._setupCanvas();
    this._setupRoot();
    this._setupSeries();
    this.final_draw();
};

/** @private */
Rubix.PieDonut.prototype._cleanElement = function() {
    this.elem.children().remove();
};

/** @private */
Rubix.PieDonut.prototype._setSize = function() {
    this.outerWidth  = this.elem.width();
    this.outerHeight = this.elem.height();

    this.width  = this.outerWidth - this.margin.left - this.margin.right;
    this.height = this.outerHeight - this.margin.top - this.margin.bottom;

    this.innerRadius = 0;
    this.outerRadius = (Math.min(this.width, this.height) * .5) - 10;

    if(this.type === 'donut') {
        this.innerRadius = this.outerRadius * .6;
    }
};

/** @private */
Rubix.PieDonut.prototype._setupCanvas = function() {
    this.elem.html('');
    this.canvas = d3.select(this.elem.get(0)).append('svg');
    this.canvas.attr('width', this.outerWidth);
    this.canvas.attr('height', this.outerHeight);

    this.canvas.append('desc').text("Powered by RubixÆžium");
};

/** @private */
Rubix.PieDonut.prototype._setupRoot = function() {
    this.root = this.canvas.append('g');
    this.root.attr('width', this.width);
    this.root.attr('height', this.height);
    this.root.attr('transform', 'translate('+this.margin.left+','+this.margin.top+')');

    if(!this.hasData) {
        this.canvas.append('text').attr('class', 'noData').attr('font-size', '30px').attr('text-anchor', 'middle').attr('transform', 'translate('+this.outerWidth/2+','+this.outerHeight/2+')').attr('font-family', 'Lato, "Lucida Grande", Arial, Helvetica, sans-serif').text("No Data");
    } else {
        this.canvas.select('.noData').style('display', 'none');
    }
};

Rubix.PieDonut.prototype._setupSeries = function() {
    this.root_piedonut_series = this.root.append('g').attr('class', 'piedonut_series').attr('transform', "translate(" + this.width / 2 + "," + this.height / 2 + ")");
};

Rubix.PieDonut.prototype.uid = function(type) {
    return 'rubixcc-pie-donut-' + type +'-' + Math.floor(2147483648*Math.random()).toString(36);
};

Rubix.PieDonut.prototype.addData = function(data) {
    this.data_changed = true;
    data.forEach(function(d) {
        d.value = +d.value;
    });

    this.data = data;

    this._setupLegend();
    this.final_draw('launch');
};

Rubix.PieDonut.prototype._setupLegend = function() {
    var self = this;
    this.legend.children().remove();
    for(var i=0; i<this.data.length; i++) {
        var color = this.opts.global_legend_color || this.data[i].color;
        this.legend.append("<div class='"+this.master_id+"-legend-labels' data-name='"+this.data[i].name+"' style='cursor: pointer; display: inline-block; font-weight: bold; margin-right: 10px; color: "+color+"'><span style='font-size: 22px;'>■ </span><span style='font-size: 12px; position:relative; top: -3px; left: -2px;'>"+this.data[i].name+"</span></div>");
    }

    $('.'+this.master_id+'-legend-labels').css({
        'opacity': 0.9
    });

    for(var id in this.legend_hidden) {
        var name = this.legend_hidden[id];
        $('[data-name='+name+']').css({
            'opacity': 0.2
        }).addClass('toggle');
    }

    $('.'+this.master_id+'-legend-labels').off().on({
        'hover': function(e) {
            var hasClass = $(this).hasClass('toggle');
            var name = $(this).attr('data-name');
            var id = '#'+self.master_id+'-'+slugify(name);

            if(hasClass) return;

            switch(e.type) {
                case 'mouseenter':
                    $(this).css({
                        'opacity': 1
                    });
                    d3.select(id).node().__onmouseover(e);
                break;
                case 'mouseleave':
                    $(this).css({
                        'opacity': 0.9
                    });
                    d3.select(id).node().__onmouseout(e)
                break;
                default:
                break;
            }
        },
        'click': function(e) {
            var hasClass = $(this).hasClass('toggle');
            var name = $(this).attr('data-name');
            var id = '#'+self.master_id+'-'+slugify(name);

            if(!hasClass) {
                $(this).css({
                    'opacity': 0.2
                });
                $(this).addClass('toggle');
                $(id).fadeOut();
                self.legend_hidden[id] = name;
            } else {
                $(this).css({
                    'opacity': 1
                });
                $(this).removeClass('toggle');
                $(id).fadeIn();
                delete self.legend_hidden[id];
            }
        }
    });
};

Rubix.PieDonut.prototype.final_draw = function(animate) {
    var self = this;
    if(this.data.length) {
        this.canvas.select('.noData').style('display', 'none');
    }
    var arc = d3.svg.arc();
    arc.innerRadius(this.innerRadius);
    arc.outerRadius(this.outerRadius);

    var pie = d3.layout.pie();
    pie.sort(null);
    pie.value(function(d) {
        return d.value;
    });

    var main = this.root_piedonut_series.selectAll('.arc')
                .data(pie(this.data), function(d) {
                    return d.data.name;
                });

    var g = main.enter().append('g')
                .attr('class', 'arc').style('position', 'relative');

    var color = d3.scale.category20();
    var path = g.attr('id', function(d) {
        return self.master_id+'-'+slugify(d.data.name);
    }).style("fill", function(d, i) { return d.data.color; }).style('stroke', 'white').append("path").attr("d", function(d) {
        return arc({
            startAngle: 0,
            endAngle: 0
        });
    })

    if(animate === 'launch') {
        this.old_pie_data = pie(this.data);
        setTimeout(function() {
            path.transition().attrTween('d', function(d) {
                var i = d3.interpolate({
                    startAngle: 0,
                    endAngle: 0
                }, {
                    startAngle: d.startAngle,
                    endAngle: d.endAngle
                });
                return function(t) {
                    return arc(i(t));
                }
            }).duration(500);
        }, 15);
    } else if(animate === 'add') {
        var t = main.transition().duration(500).each('end', function() {
            self.old_pie_data = pie(self.data);
        });
        t.select('path').attrTween('d', function(d, k) {
            var startAngle = 0;
            var endAngle = 0;
            try {
                startAngle = self.old_pie_data[k].startAngle;
                endAngle = self.old_pie_data[k].endAngle;
            } catch(e) {
                startAngle = self.old_pie_data[self.old_pie_data.length-1].startAngle;
                endAngle = self.old_pie_data[self.old_pie_data.length-1].endAngle;
                // do nothing
            }
            var i = d3.interpolate({
                startAngle: startAngle,
                endAngle: endAngle
            }, {
                startAngle: d.startAngle,
                endAngle: d.endAngle
            });

            if(d.data.hasOwnProperty('_remove')) {
                self.data.splice(k, 1);
            }
            self._setupLegend();
            return function(t) {
                return arc(i(t));
            }
        });
    } else {
        this.old_pie_data = pie(this.data);
        path.attr('d', function(d) {
            return arc(d);
        });
    }

    var mouseover = 'mouseover',
        mousemove = 'mousemove',
        mouseout  = 'mouseout';
    if(this.is_touch_device) {
        mouseover = 'touchstart';
        mousemove = 'touchstart';
        mouseout  = 'touchend';
    }

    var timer;

    g.on(mouseover, function(d) {
        clearTimeout(timer);
        var centroid = arc.centroid(d);
        var h = Math.sqrt(Math.pow(centroid[0], 2) + Math.pow(centroid[1], 2));

        var x = centroid[0]/h * 8;
        var y = centroid[1]/h * 8;
        if(!self.is_touch_device) {
            d3.select(this).transition().duration(150).attr('transform', "translate(" + [x, y] + ")");
        }
    });

    g.on(mousemove, function(d, i) {
        clearTimeout(timer);
        if(self.is_touch_device) {
            var coordinates = d3.touches(this, self.d3_eventSource().changedTouches)[0];
        } else {
            var coordinates = d3.mouse(this);
        }
        self.tooltip.css('display', useTable);
        self.tooltip.css({
            'left': (self.width/2 - self.margin.left/2) + coordinates[0],
            'top' : (self.height/2 - self.margin.top/2) + coordinates[1]
        });

        if(self.opts.tooltip.customPlaceholder) {
            var tip = self.opts.tooltip.customPlaceholder.replace("%n", d.data.name);
            tip = tip.replace("%v", d.data.value);
            tip = tip.replace("%c", d.data.color);
            self.tooltip.html(tip);
        } else {
            self.tooltip.html("<div style='color: "+d.data.color+";'><b><span style='font-size: 22px;'>■ </span><span style='position:relative; top: -3px; left: -2px;'>"+d.data.name+" :</span></b> <span style='position:relative; top: -3px; left: -2px;'>"+d.data.value+"</span></div>");
        }

        d3.select(this).style('fill', d.data.color);
    });

    g.on(mouseout, function(d, i) {
        var $this = this;
        if(self.is_touch_device) {
            timer = setTimeout(function() {
                self.tooltip.hide();
            }, 3000);

            setTimeout(function() {
                d3.select($this).style('fill', d.data.color);
            }, 3000);
        } else {
            self.tooltip.hide();
            d3.select($this).style('fill', d.data.color);
            d3.select($this).transition().duration(150).attr('transform', 'translate(0, 0)');
        }
    });

    main.exit().remove();

    for(var i in this.legend_hidden) {
        $(i).hide();
    }
};

Rubix.PieDonut.prototype.removePoint = function(ref) {
    var removed = false;
    for(var i=0; i<this.data.length; i++) {
        if(this.data[i].name === ref) {
            removed = true;
            this.data[i].value = 0;
            this.data[i]._remove = true;
            break;
        }
    }

    if(!removed) return;

    this.final_draw('add');
};

Rubix.PieDonut.prototype.updatePoint = function(data) {
    this.addPoint(data);
};

Rubix.PieDonut.prototype.addPoint = function(data) {
    this.data_changed = true;
    var updated = false;
    for(var i=0; i<this.data.length; i++) {
        if(this.data[i].name === data.name) {
            updated = true;
            this.data[i].value = +data.value;
            break;
        }
    }

    if(!updated) {
        this.data.push(data);
    }

    this.final_draw('add');
};

window.Rubix = window.Rubix || {};

/**
 * @param {Rubix} rubix
 * @param {Object} opts
 * @constructor
 */
Rubix.LineSeries = function(rubix, opts) {
    this.opts = opts;
    this.opts.color = this.opts.color || 'steelblue';
    this.opts.marker = this.opts.marker || 'circle';
    this.opts.scatter = this.opts.scatter || false;
    this.opts.noshadow = this.opts.noshadow || false;
    this.show_markers = this.opts.show_markers;
    this.opts.strokewidth = this.opts.strokewidth || 1;

    if(!this.opts.hasOwnProperty('name')) throw new Error('LineSeries should have a \'name\' property');

    this.name = this.opts.name;

    this.chart_hidden = false;
    this.temp_stack = [];

    this.setup = false;
    this._setup(rubix);
};

/**
 * @param {Rubix} rubix
 * @private
 */
Rubix.LineSeries.prototype._setup = function(rubix) {
    this.rubix = rubix;

    this.root   = this.rubix.root;
    this.data   = this.rubix.data;
    this.width  = this.rubix.width;
    this.height = this.rubix.height;
    this.line_series = this.rubix.root_line_series;
    this.show_markers = (this.show_markers === undefined) ? this.rubix.show_markers : this.show_markers;

    this.master_detail = this.rubix.master_detail;

    if(this.master_detail) {
        this.md_root = this.rubix.md_root;
    }

    if(!this.id) {
        this.id = this.rubix.uid('line');
    }

    /** separator */
    var self = this;
    this.line = d3.svg.line();
    this.line.defined(function(d) {
        return d.x !== null && d.y !== null;
    });
    this.line.x(function(d) {
        if(self.rubix.opts.invertAxes) {
            return self.rubix.x(d.y);
        }

        if(self.rubix.axis.x.range === 'column' || self.rubix.axis.x.range === 'bar') {
            return self.rubix.x(d.x) + self.rubix.x.rangeBand()/2;
        }
        return self.rubix.x(d.x);
    });
    this.line.y(function(d) {
        if(self.rubix.opts.invertAxes) {
            if(self.rubix.axis.y.range === 'column' || self.rubix.axis.y.range === 'bar') {
                return self.rubix.y(d.x) + self.rubix.y.rangeBand()/2;
            }
            return self.rubix.y(d.x);
        }
        return self.rubix.y(d.y);
    });
    this.line.interpolate(this.rubix.interpolate);

    if(this.master_detail) {
        this.master_line = d3.svg.line();
        this.master_line.defined(function(d) {
            return d.x !== null && d.y !== null;
        });
        this.master_line.x(function(d) {
            if(self.rubix.opts.invertAxes) {
                return self.rubix.x2(d.y);
            }
            return self.rubix.x2(d.x);
        });
        this.master_line.y(function(d) {
            if(self.rubix.opts.invertAxes) {
                return self.rubix.y2(d.x);
            }
            return self.rubix.y2(d.y);
        });
        this.master_line.interpolate(this.rubix.interpolate);
    }
};

// Alias for draw
Rubix.LineSeries.prototype.redraw = function(rubix) {
    this._setup(rubix);
    this.draw();
};

// Alias for draw
Rubix.LineSeries.prototype.noRedraw = function(rubix) {
    this._setup(rubix);
    this.draw(true);
};

/**
 * @param {Array|Object} data
 */
Rubix.LineSeries.prototype.addData = function(data) {
    this.rubix.data_changed = true;
    if(!(data instanceof Array)) {
        if(!(data instanceof Object)) {
            throw new Error("Data must be an array or object");
        } else {
            if(!(data.hasOwnProperty('x') || data.hasOwnProperty('y'))) {
                throw new Error("Object must be in the form: {x: ..., y: ...}");
            }

            data = [data];
        }
    }

    if(!data.length) return;

    if(!this.rubix.opts.noSort) {
        data.sort(function(a, b) {
            if(a.x > b.x) {
                return 1;
            } else if(a.x === b.x) {
                return 0;
            } else {
                return -1;
            }
        });
    }

    this.data[this.name] = data;

    this.rubix.resetAxis(false);
    this.rubix.forceRedraw();
    this._animate_draw();
};

Rubix.LineSeries.prototype.draw = function(forced) {
    if(!this.name) return;
    if(!this.data) return;
    if(!this.data.hasOwnProperty(this.name)) return;
    if(!this.data[this.name].length) return;

    var self = this;

    var isConstructed = this.line_series.selectAll('.'+this.id)[0].length;

    if(!isConstructed) {
        this.line_series.selectAll('.'+this.id+'.clipped').remove();
        if(!this.opts.scatter) {
            if(!this.opts.noshadow) {
                this.strokePath1 = this.line_series.append('path').attr('class', this.id+' line').datum(this.data[this.name]).attr('d', this.line).attr('stroke', 'black').attr('fill', 'none').attr('stroke-linecap', 'round').attr('stroke-width', 5 * this.opts.strokewidth).attr('stroke-opacity', 0.05000000000000001).attr('transform', 'translate(1,1)');
                this.strokePath2 = this.line_series.append('path').attr('class', this.id+' line').datum(this.data[this.name]).attr('d', this.line).attr('stroke', 'black').attr('fill', 'none').attr('stroke-linecap', 'round').attr('stroke-width', 3 * this.opts.strokewidth).attr('stroke-opacity', 0.1).attr('transform', 'translate(1,1)');
                this.strokePath3 = this.line_series.append('path').attr('class', this.id+' line').datum(this.data[this.name]).attr('d', this.line).attr('stroke', 'black').attr('fill', 'none').attr('stroke-linecap', 'round').attr('stroke-width', 1 * this.opts.strokewidth).attr('stroke-opacity', 0.15000000000000002).attr('transform', 'translate(1,1)');
            }
            this.linePath = this.line_series.append('path').datum(this.data[this.name]).attr('class', this.id+' line').attr('stroke', this.opts.color).attr('fill', 'none').attr('stroke-linecap', 'round').attr('d', this.line).attr('stroke-width', 2 * this.opts.strokewidth);
        }

        if(this.master_detail) {
            if(!this.opts.scatter) {
                this.md_root.select('.md-layers > .md_line_series').selectAll('.'+this.id+'.clipped').remove();
                this.masterLinePath = this.md_root.select('.md-layers > .md_line_series').append('path').datum(this.data[this.name]).attr('class', this.id+' line').attr('stroke', this.opts.color).attr('fill', 'none').attr('stroke-linecap', 'round').attr('d', this.master_line).attr('stroke-width', 2 * this.opts.strokewidth);
                this.masterLinePath.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip)');
                this.masterLinePath.classed('clipped', true);
            } else {
                this.md_root.select('.md-layers > .md_line_series').attr('clip-path', 'url(#'+self.rubix.master_id+'-clip)');
                var self = this;
                var datum = this.data[this.name];
                this.markers_master = this.md_root.select('.md-layers > .md_line_series').selectAll('.'+this.id+'-marker');
                switch(this.opts.marker) {
                    case 'circle':
                    case 'cross':
                    case 'square':
                    case 'diamond':
                    case 'triangle-up':
                    case 'triangle-down':
                        var symbol = d3.svg.symbol();
                        symbol.type(this.opts.marker);

                        var symbolType = this.markers_master.data(datum);
                        symbolType.enter().append('path')
                              .attr('d', symbol)
                              .attr('class', this.id + '-marker')
                              .attr('fill', this.opts.color)
                              .style('display', function(d) {
                                    if(d.y === null) {
                                        return 'none';
                                    }
                                    return null;
                              })
                              .attr('stroke', 'none')
                              .attr('transform', function(d) {
                                    var val = d.y;
                                    if(isNaN(val)) {
                                        val = 0;
                                    }
                                    if(self.rubix.opts.invertAxes) {
                                        var _y = self.rubix.y(d.x);
                                        var _x = self.rubix.x(val);
                                        if(self.rubix.axis.y.range === 'column' || self.rubix.axis.y.range === 'bar') {
                                            _y += self.rubix.y.rangeBand()/2;
                                        }
                                    } else {
                                        var _y = self.rubix.y2(val);
                                        var _x = self.rubix.x2(d.x);
                                        if(self.rubix.axis.x.range === 'column' || self.rubix.axis.x.range === 'bar') {
                                            _x += self.rubix.x.rangeBand()/2;
                                        }
                                    }
                                    return 'translate('+_x+','+_y+')';
                              });
                        symbolType.exit().remove();
                    break;
                    default:
                        throw new Error('Unknown marker type : ' + this.opts.marker);
                    break;
                }
            }

            if(this.dataChanged) {
                this.rubix.resetExtent();
                this.dataChanged = false;
            }

            if(!this.opts.scatter) {
                if(!this.opts.noshadow) {
                    this.strokePath1.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip)');
                    this.strokePath2.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip)');
                    this.strokePath3.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip)');
                }
                this.linePath.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip)');

                if(!this.opts.noshadow) {
                    this.strokePath1.classed('clipped', true);
                    this.strokePath2.classed('clipped', true);
                    this.strokePath3.classed('clipped', true);
                }
                this.linePath.classed('clipped', true);
            }
        }

        this.setup = true;
    } else {
        this.rubix.runCommand('globalRedraw');
    }

    this.rubix.resetFocus(forced);
};

/**
 * @param {Array|Object} data
 */
Rubix.LineSeries.prototype.update = function(data) {
    if(!(data instanceof Array)) {
        if(!(data instanceof Object)) {
            throw new Error("Data must be an array or object");
        } else {
            if(!(data.hasOwnProperty('x') || data.hasOwnProperty('y'))) {
                throw new Error("Object must be in the form: {x: ..., y: ...}");
            }

            data = [data];
        }
    }

    if(!this.rubix.opts.noSort) {
        data.sort(function(a, b) {
            if(a.x > b.x) {
                return 1;
            } else if(a.x === b.x) {
                return 0;
            } else {
                return -1;
            }
        });
    }

    this.data[this.name] = data;

    if(this.setup) {
        this._animate_draw();
    } else {
        this.rubix.resetAxis(false);
        this.rubix.forceRedraw();
    }
};

/**
 * Alias for addPoint
 * @param {Object} data
 * @param {?Boolean} shift
 * @param {?Boolean} noRedraw
 */
Rubix.LineSeries.prototype.updatePoint = function(data, shift, noRedraw) {
    this.addPoint(data, shift, noRedraw);
};

/**
 * @param {*} ref
 * @param {?Boolean} noRedraw
 */
Rubix.LineSeries.prototype.removePoint = function(ref, noRedraw) {
    if(this.chart_hidden) {
        this.temp_stack.push({
            type: 'removePoint',
            ref: ref
        });
        return;
    }

    if(!this.data[this.name]) {
        this.data[this.name] = [];
    }

    var removed = false;
    for(var i=0; i<this.data[this.name].length; i++) {
        if(this.data[this.name][i].x === ref) {
            this.data[this.name].splice(i, 1);
            removed = true;
            break;
        }
    }

    if(this.master_detail) {
        this.dataChanged = true;
    }

    if(noRedraw) return;

    if(this.setup) {
        this._animate_draw();
    } else {
        this.rubix.resetAxis(true);
        this.rubix.forceRedraw();
    }
};

/**
 * @param {Object} data
 * @param {?Boolean} shift
 * @param {?Boolean} noRedraw
 */
Rubix.LineSeries.prototype.addPoint = function(data, shift, noRedraw) {
    this.rubix.data_changed = true;
    if(!(data instanceof Object) || (data instanceof Array)) {
        throw new Error("Object required for addPoint");
    }
    if(!(data.hasOwnProperty('x') || data.hasOwnProperty('y'))) {
        throw new Error("Object must be in the form: {x: ..., y: ...}");
    }

    if(this.chart_hidden) {
        this.temp_stack.push({
            type: 'addPoint',
            data: data,
            shift: shift
        });
        return;
    }

    if(!this.data[this.name]) {
        this.data[this.name] = [];
    }

    var added = false;
    for(var i=0; i<this.data[this.name].length; i++) {
        if(this.data[this.name][i].x === data.x) {
            this.data[this.name][i].y = data.y;
            added = true;
            break;
        }
    }

    if(!added) {
        this.data[this.name].push(data);
    }

    this.data[this.name].sort(function(a, b) {
        if(a.x > b.x) {
            return 1;
        } else if(a.x === b.x) {
            return 0;
        } else {
            return -1;
        }
    });

    if(this.rubix.opts.interval) {
        if(this.rubix.opts.interval < this.data[this.name].length) {
            this.data[this.name].shift();
        }
    } else {
        if(shift) {
            this.data[this.name].shift();
        }
    }

    if(this.master_detail) {
        this.dataChanged = true;
    }

    if(noRedraw) return;
    if(this.setup) {
        this._animate_draw();
    } else {
        this.rubix.resetAxis(true);
        this.rubix.forceRedraw();
    }
};

Rubix.LineSeries.prototype._animate_draw = function() {
    var text = this.root.selectAll('.y.axis').selectAll('text')[0];
    var width = [];
    for(var i=0; i<text.length; i++) {
        width.push(text[i].getBBox().width);
    }
    var origMaxWidth = d3.max(width);

    this.rubix.resetAxis(true);

    this.rubix.runCommand('globalRedraw');

    text = this.root.selectAll('.y.axis').selectAll('text')[0];
    width = [];
    for(var i=0; i<text.length; i++) {
        width.push(text[i].getBBox().width);
    }

    var maxWidth = d3.max(width);

    this.rubix.marginLeft(maxWidth);
    this.rubix.resetFocus();
};

Rubix.LineSeries.prototype.hidden = function() {
    this.chart_hidden = true;
};

Rubix.LineSeries.prototype.show = function() {
    this.chart_hidden = false;
    while(this.temp_stack.length>1) {
        var rawData = this.temp_stack.shift();
        if(rawData.type === 'addPoint') {
            this.addPoint(rawData.data, rawData.shift, true);
        } else {
            this.removePoint(rawData.ref);
        }
    }
    if(this.temp_stack.length) {
        var rawData = this.temp_stack.shift();
        if(rawData.type === 'addPoint') {
            this.addPoint(rawData.data, rawData.shift, true);
        } else {
            this.removePoint(rawData.ref);
        }
    }
};

Rubix.LineSeries.prototype.globalRedraw = function(rubix) {
    this.line_series.selectAll('.'+this.id+'.line').attr('d', this.line);
};

Rubix.LineSeries.prototype.forceRedraw = function(rubix) {
    this.redraw(rubix);
};

Rubix.LineSeries.prototype.on_complete_draw = function() {
    if(!this.setup) return;
    var self = this;
    var datum = this.data[this.name];
    if(this.opts.marker !== undefined && this.show_markers) {
        if(this.master_detail) {
            this.rubix.symbols.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip-symbols)');
        }
        this.markers = this.rubix.symbols.selectAll('.'+this.id+'-marker');
        switch(this.opts.marker) {
            case 'circle':
            case 'cross':
            case 'square':
            case 'diamond':
            case 'triangle-up':
            case 'triangle-down':
                var symbol = d3.svg.symbol();
                symbol.type(this.opts.marker);

                var symbolType = this.markers.data(datum);
                symbolType.enter().append('path')
                      .attr('d', symbol)
                      .attr('class', this.id + '-marker')
                      .attr('fill', this.opts.color)
                      .style('display', function(d) {
                            if(d.y === null) {
                                return 'none';
                            }
                            return null;
                      })
                      .attr('stroke', 'white')
                      .attr('transform', function(d) {
                            var val = d.y;
                            if(isNaN(val)) {
                                val = 0;
                            }
                            if(self.rubix.opts.invertAxes) {
                                var _y = self.rubix.y(d.x);
                                var _x = self.rubix.x(val);
                                if(self.rubix.axis.y.range === 'column' || self.rubix.axis.y.range === 'bar') {
                                    _y += self.rubix.y.rangeBand()/2;
                                }
                            } else {
                                var _y = self.rubix.y(val);
                                var _x = self.rubix.x(d.x);
                                if(self.rubix.axis.x.range === 'column' || self.rubix.axis.x.range === 'bar') {
                                    _x += self.rubix.x.rangeBand()/2;
                                }
                            }
                            return 'translate('+_x+','+_y+')';
                      });
                symbolType.exit().remove();
            break;
            default:
                throw new Error('Unknown marker type : ' + this.opts.marker);
            break;
        }
    }
};

Rubix.LineSeries.prototype.setupFocus = function() {
    if(!this.setup) return;
    if(this.focus) this.focus.remove();
    this.focus = this.rubix.focus_group.append('g');
    this.focus.attr('class', 'focus');
    this.focus.style('display', 'none');
    switch(this.opts.marker) {
        case 'circle':
        case 'cross':
        case 'square':
        case 'diamond':
        case 'triangle-up':
        case 'triangle-down':
            var symbol = d3.svg.symbol();
            symbol.type(this.opts.marker);
            var path = this.focus.append('path').attr('d', symbol).attr('fill', this.opts.color).attr('stroke', 'white').attr('stroke-width', 2);
        break;
        default:
            throw new Error('Unknown marker type : ' + this.opts.marker);
        break;
    }
};

Rubix.LineSeries.prototype.on_focus = function() {
    if(!this.setup) return;
    this.linePath.attr('stroke-width', 2 * this.opts.strokewidth);
};

Rubix.LineSeries.prototype.off_focus = function() {
    if(!this.setup) return;
    this.linePath.attr('stroke-width', 2 * this.opts.strokewidth);
};
window.Rubix = window.Rubix || {};

/**
 * @param {Rubix} rubix
 * @param {Object} opts
 * @constructor
 */
Rubix.ColumnSeries = function(rubix, opts) {
    this.type = 'column_series';
    this.opts = opts;
    this.opts.color = this.opts.color || 'steelblue';
    this.opts.marker = this.opts.marker || 'circle';
    this.opts.nostroke = this.opts.nostroke || '';
    this.opts.fillopacity = this.opts.fillopacity || 0.85;
    this.show_markers = this.opts.show_markers;

    if(!this.opts.hasOwnProperty('name')) throw new Error('ColumnSeries should have a \'name\' property');

    this.name = this.opts.name;

    this.chart_hidden = false;
    this.temp_stack = [];

    this.setup = false;
    this.last_stack = [];
    this.last_stack_name = '';
    this.last_stack_added = false;

    this.count = 0;

    this._setup(rubix);
};

/**
 * @param {Rubix} rubix
 * @private
 */
Rubix.ColumnSeries.prototype._setup = function(rubix) {
    this.rubix = rubix;
    this.rubix.stacked = true;

    this.root   = this.rubix.root;
    this.data   = this.rubix.data;
    this.width  = this.rubix.width;
    this.height = this.rubix.height;
    this.stack  = this.rubix.column_stack;
    this.offset = this.rubix.column_offset;
    this.cb_series = this.rubix.root_cb_series;
    this.column_stack = this.rubix.column_stack_data;
    this.show_markers = (this.show_markers === undefined) ? this.rubix.show_markers : this.show_markers;

    this.grouped = this.rubix.grouped;

    if(!this.id) {
        this.id = this.rubix.uid('stacked-column');
    }
};

// Alias for draw
Rubix.ColumnSeries.prototype.redraw = function(rubix) {
    this._setup(rubix);
    this.draw();
};

Rubix.ColumnSeries.prototype.noRedraw = function(rubix) {
    this._setup(rubix);
    this.draw(true);
};

/**
 * @param {Array|Object} data
 */
Rubix.ColumnSeries.prototype.addData = function(data) {
    this.rubix.data_changed = true;
    if(!(data instanceof Array)) {
        if(!(data instanceof Object)) {
            throw new Error("Data must be an array or object");
        } else {
            if(!(data.hasOwnProperty('x') || data.hasOwnProperty('y'))) {
                throw new Error("Object must be in the form: {x: ..., y: ...}");
            }

            data = [data];
        }
    }

    if(!data.length) return;

    if(!this.rubix.opts.noSort) {
        data.sort(function(a, b) {
            if(a.x > b.x) {
                return 1;
            } else if(a.x === b.x) {
                return 0;
            } else {
                return -1;
            }
        });
    }

    var maxLen = 0, columns = [];
    for(var i in this.data) {
        var len = this.data[i].length;
        if(len > maxLen) {
            maxLen = len;
            columns = [];
            for(var k=0; k<maxLen; k++) {
                columns.push(this.data[i][k].x);
            }
        }
    }

    this.rubix.maxLen = maxLen;

    this.data[this.name] = data;

    for(var i in this.data) {
        var len = this.data[i].length;
        if(len < maxLen) {
            var dup_columns = columns.concat();
            for(var j=0; j<this.data[i].length; j++) {
                var column_index = dup_columns.indexOf(this.data[i][j].x);
                dup_columns.splice(column_index, 1);
            }
            for(var j=0; j<dup_columns.length; j++) {
                this.rubix.charts[i].addPoint({
                    x: dup_columns[j],
                    y: null
                });
            }
        }
    }

    this.column_stack.push({
        key: this.name,
        color: this.opts.color,
        marker: this.opts.marker,
        values: this.data[this.name],
        orderKey: this.column_stack.length
    });

    this.rubix.resetAxis();
    this.rubix.forceRedraw();
    this._animate_draw();
};

Rubix.ColumnSeries.prototype._createRect = function() {
    var self = this;
    var strokecolor = 'white';
    if(this.opts.nostroke) strokecolor = 'none';
    var rect = this.columnGroup.selectAll('rect').data(function(d) { return d.values; }).enter().append('rect').attr('stroke', strokecolor);
    if(this.grouped) {
        if(this.rubix.opts.invertAxes) {
            rect.attr('x', function(d, i, j) {
                var val = self.rubix.x(0);
                if(isNaN(val)) {
                    return null;
                }
                return val;
            });
            rect.attr('y', function(d, i, j) {
                return self.rubix.y(d.x) + self.rubix.y.rangeBand() / self.layers.length * j;
            });
            rect.attr('class', function(d, i, j) {
                return 'column-' + ((self.rubix.y(d.x) + (self.rubix.y.rangeBand()/self.layers.length) * (self.layers.length-1)) + self.rubix.y.rangeBand()/(2*self.layers.length));
            });
            rect.attr("width", function(d) {
                var val = Math.abs(self.rubix.x(d.y) - self.rubix.x(0));
                if(isNaN(val)) {
                    return null;
                }
                return val;
            });
            rect.attr('height', self.rubix.y.rangeBand() / self.layers.length);
            rect.attr('transform', function(d) {
                var val = self.rubix.x(d.y_new) - self.rubix.x(0);
                if(d.y < 0) {
                    return 'translate('+(-Math.abs(val))+',0)';
                } else {
                    return 'translate(0,0)';
                }
            });
        } else {
            rect.attr('x', function(d, i, j) {
                return self.rubix.x(d.x) + self.rubix.x.rangeBand() / self.layers.length * j;
            });
            rect.attr('y', function(d) {
                var val = self.rubix.y(0);
                if(isNaN(val)) {
                    return null;
                }
                return val;
            });
            rect.attr('class', function(d, i, j) {
                return 'column-' + ((self.rubix.x(d.x) + (self.rubix.x.rangeBand()/self.layers.length) * (self.layers.length-1)) + self.rubix.x.rangeBand()/(2*self.layers.length));
            });
            rect.attr("width", self.rubix.x.rangeBand() / self.layers.length)
            rect.attr('height', function(d) {
                var val = Math.abs(self.rubix.y(d.y) - self.rubix.y(0));
                if(isNaN(val)) {
                    return null;
                }
                return val;
            });
            rect.attr('transform', function(d) {
                var val = self.rubix.y(d.y_new) - self.rubix.y(0);
                if(d.y > 0) {
                    return 'translate(0,'+(-Math.abs(val))+')';
                } else {
                    return 'translate(0,0)';
                }
            });
        }
    } else {
        if(this.rubix.opts.invertAxes) {
            rect.attr('x', function(d) {
                var val = self.rubix.x(d.y0);
                if(isNaN(val)) {
                    return null;
                }
                return val;
            });
            rect.attr('y', function(d) {
                return self.rubix.y(d.x) || 0;
            });
            rect.attr('class', function(d) {
                return 'column-' + (self.rubix.y(d.x) + self.rubix.y.rangeBand()/2);
            });
            rect.attr("width", function(d) {
                var val = Math.abs(self.rubix.x(d.y0)-self.rubix.x(d.y+d.y0));
                if(isNaN(val)) {
                    return null;
                }
                return val;
            });
            rect.attr('height', self.rubix.y.rangeBand());
            rect.attr('transform', function(d) {
                if(d.y < 0) {
                    var val = Math.abs(self.rubix.x(d.y0)-self.rubix.x(d.y+d.y0));
                    return 'translate('+(-val)+',0)';
                }
                return null;
            });
        } else {
            rect.attr('x', function(d) {
                return self.rubix.x(d.x);
            });
            rect.attr('y', function(d) {
                var val = self.rubix.y(d.y0);
                if(isNaN(val)) {
                    return null;
                }
                return val;
            });
            rect.attr('class', function(d) {
                return 'column-' + ((self.rubix.x(d.x) + self.rubix.x.rangeBand()/2));
            });
            rect.attr("width", self.rubix.x.rangeBand())
            rect.attr('height', function(d) {
                var val = Math.abs(self.rubix.y(d.y0)-self.rubix.y(d.y+d.y0));
                if(isNaN(val)) {
                    return null;
                }
                return val;
            });
            rect.attr('transform', function(d) {
                if(d.y > 0) {
                    var val = Math.abs(self.rubix.y(d.y0)-self.rubix.y(d.y+d.y0));
                    return 'translate(0,'+(-val)+')';
                }
                return null;
            });
        }
    }
};

Rubix.ColumnSeries.prototype.draw = function(forced) {
    try {
        if(!this.name) return;
        if(!this.data) return;
        if(!this.data.hasOwnProperty(this.name)) return;
        if(!this.data[this.name].length) return;

        var oldLayers = this.layers;

        try {
            this.layers = this.stack(this.column_stack);
        } catch(e) {
            // data un-available. retaining old layer.
            this.layers = oldLayers;
        }

        if(!this.grouped) {
            var max = this.rubix.maxLen;
            for(var i=0; i<max; i++) {
                var x = this.layers[0].values[i].x;
                var ceiling = null;
                for(var j=0; j<this.layers.length; j++) {
                    for(var k=0; k<max; k++) {
                        try {
                            if(this.layers[j].values[k].x === x) {
                                if(this.layers[j].values[k].y >= 0) {
                                    if(this.layers[j].values[k].y === null) {
                                        this.layers[j].values[k].y0 = null;
                                    } else {
                                        if(ceiling === null) ceiling = 0;
                                        this.layers[j].values[k].y0 = ceiling;
                                        ceiling += this.layers[j].values[k].y;
                                    }
                                }
                                break;
                            }
                        } catch(e) {
                            this.layers[j].values.push({
                                x: x,
                                y: null,
                                y0: null,
                                y_new: null
                            });
                        }
                    }
                }
                ceiling = null;
                for(var j=this.layers.length-1; j>=0; j--) {
                    for(var k=0; k<max; k++) {
                        try {
                            if(this.layers[j].values[k].x === x) {
                                if(this.layers[j].values[k].y < 0) {
                                    if(this.layers[j].values[k].y === null) {
                                        this.layers[j].values[k].y0 = null;
                                    } else {
                                        if(ceiling === null) ceiling = 0;
                                        this.layers[j].values[k].y0 = -ceiling;
                                        ceiling += Math.abs(this.layers[j].values[k].y);
                                    }
                                }
                                break;
                            }
                        } catch(e) {
                            this.layers[j].values.push({
                                x: x,
                                y: null,
                                y0: null,
                                y_new: null
                            });
                        }
                    }
                }
            }
        }

        var self = this;
        var isConstructed = this.cb_series.selectAll('.'+this.id)[0].length;
        if(!isConstructed) {
            try {
                this.cb_series.selectAll('.column-layer').remove();
                var p = this.cb_series.selectAll('.column-layer').data(this.layers, function(d, i) {
                    if(d.key === self.name) {
                        self.count = i;
                    }
                    return d.key;
                });

                this.columnGroup = p.enter().append('g');
                this.columnGroup.attr('class', 'column-layer')
                           .attr('fill', function(d) { return d.color; }).attr('fill-opacity', this.opts.fillopacity);

                this._createRect();

                p.exit().remove();
            } catch(e) {
                // do nothing
            }
            this.setup = true;
        } else {
            this.rubix.runCommand('globalRedraw');
        }

        this.rubix.resetFocus(forced);
    } catch(e) {
        // do nothing
    }
};

/**
 * @param {Array|Object} data
 */
Rubix.ColumnSeries.prototype.update = function(data) {
    if(!(data instanceof Array)) {
        if(!(data instanceof Object)) {
            throw new Error("Data must be an array or object");
        } else {
            if(!(data.hasOwnProperty('x') || data.hasOwnProperty('y'))) {
                throw new Error("Object must be in the form: {x: ..., y: ...}");
            }

            data = [data];
        }
    }

    if(!this.rubix.opts.noSort) {
        data.sort(function(a, b) {
            if(a.x > b.x) {
                return 1;
            } else if(a.x === b.x) {
                return 0;
            } else {
                return -1;
            }
        });
    }

    this.data[this.name] = data;

    if(this.setup) {
        this._animate_draw();
    } else {
        this.rubix.resetAxis(true);
        this.rubix.forceRedraw();
    }
};

/**
 * @param {Object} data
 * @param {?Boolean} shift
 * @param {?Boolean} noRedraw
 */
Rubix.ColumnSeries.prototype.updatePoint = function(data, shift, noRedraw) {
    this.addPoint(data, shift, noRedraw);
};

/**
 * @param {*} ref
 * @param {?Boolean} noRedraw
 */
Rubix.ColumnSeries.prototype.removePoint = function(ref, noRedraw) {
    if(this.chart_hidden) {
        this.temp_stack.push({
            type: 'removePoint',
            ref: ref
        });
        return;
    }

    if(!this.data[this.name]) {
        this.data[this.name] = [];
    }

    var removed = false, pos = 0;
    for(var i=0; i<this.data[this.name].length; i++) {
        if(this.data[this.name][i].x === ref) {
            this.data[this.name][i].y = null;
            this.data[this.name][i].y0 = null;
            this.data[this.name][i].y_new = null;
            pos = i;
            removed = true;
            break;
        }
    }

    if(!removed) return;

    var found = false;
    for(var i=0; i<this.column_stack.length; i++) {
        var st = this.column_stack[i];
        try {
            if(st.values[pos].x === ref) {
                if(st.values[pos].y !== null) {
                    found = true;
                    break;
                }
            }
        } catch(e) {
            // do nothing
        }
    }

    if(!found) {
        for(var i=0; i<this.column_stack.length; i++) {
            this.column_stack[i].values.splice(pos, 1);
        }
    }

    var maxLen = 0;
    for(var i in this.data) {
        var len = this.data[i].length;
        if(len > maxLen) {
            maxLen = len;
        }
    }

    this.rubix.maxLen = maxLen;

    if(noRedraw) return;

    this.rubix.resetAxis();
    this.rubix.forceRedraw();
    this._animate_draw();
};

/**
 * @param {Object} data
 * @param {?Boolean} shift
 * @param {?Boolean} noRedraw
 */
Rubix.ColumnSeries.prototype.addPoint = function(data, shift, noRedraw) {
    this.rubix.data_changed = true;
    if(!(data instanceof Object) || (data instanceof Array)) {
        throw new Error("Object required for addPoint");
    }
    if(!(data.hasOwnProperty('x') || data.hasOwnProperty('y'))) {
        throw new Error("Object must be in the form: {x: ..., y: ...}");
    }

    if(this.chart_hidden) {
        this.temp_stack.push({
            type: 'addPoint',
            data: data,
            shift: shift
        });
        return;
    }

    if(!this.data[this.name]) {
        this.data[this.name] = [];
    }

    var added = false;
    for(var i=0; i<this.data[this.name].length; i++) {
        if(this.data[this.name][i].x === data.x) {
            this.data[this.name][i].y = data.y;
            added = true;
            break;
        }
    }

    if(!added) {
        this.data[this.name].push(data);
    }

    this.data[this.name].sort(function(a, b) {
        if(a.x > b.x) {
            return 1;
        } else if(a.x === b.x) {
            return 0;
        } else {
            return -1;
        }
    });

    added = false;
    for(var i=0; i<this.column_stack.length; i++) {
        if(this.column_stack[i].key === this.name) {
            added = true;
            break;
        }
    }

    if(this.rubix.opts.interval) {
        if(this.rubix.opts.interval < this.data[this.name].length) {
            this.data[this.name].shift();
        }
    } else {
        if(shift) {
            this.data[this.name].shift();
        }
    }

    var maxLen = 0, columns = [];
    for(var i in this.data) {
        var len = this.data[i].length;
        if(len > maxLen) {
            maxLen = len;
            columns = [];
            for(var k=0; k<maxLen; k++) {
                columns.push(this.data[i][k].x);
            }
        }
    }

    this.rubix.maxLen = maxLen;

    for(var i in this.data) {
        var len = this.data[i].length;
        if(len < maxLen) {
            var dup_columns = columns.concat();
            for(var j=0; j<this.data[i].length; j++) {
                var column_index = dup_columns.indexOf(this.data[i][j].x);
                dup_columns.splice(column_index, 1);
            }
            for(var j=0; j<dup_columns.length; j++) {
                this.rubix.charts[i].addPoint({
                    x: dup_columns[j],
                    y: null
                });
            }
        }
    }

    if(noRedraw) return;

    this.rubix.resetAxis();
    this.rubix.forceRedraw();
    this._animate_draw();
};

Rubix.ColumnSeries.prototype._animate_draw = function() {
    try {
        var text = this.root.selectAll('.y.axis').selectAll('text')[0];
        var width = [];
        for(var i=0; i<text.length; i++) {
            width.push(text[i].getBBox().width);
        }
        var origMaxWidth = d3.max(width);

        this.rubix.resetAxis(true);

        this.rubix.runCommand('globalRedraw');

        text = this.root.selectAll('.y.axis').selectAll('text')[0];
        width = [];
        for(var i=0; i<text.length; i++) {
            width.push(text[i].getBBox().width);
        }

        var maxWidth = d3.max(width);

        this.rubix.marginLeft(maxWidth);
        this.rubix.resetFocus();
    } catch(e) {
        // do nothing
    }
};

Rubix.ColumnSeries.prototype.hidden = function() {
    this.chart_hidden = true;
};

Rubix.ColumnSeries.prototype.show = function() {
    this.chart_hidden = false;
    while(this.temp_stack.length>1) {
        var rawData = this.temp_stack.shift();
        if(rawData.type === 'addPoint') {
            this.addPoint(rawData.data, rawData.shift, true);
        } else {
            this.removePoint(rawData.ref);
        }
    }
    if(this.temp_stack.length) {
        var rawData = this.temp_stack.shift();
        if(rawData.type === 'addPoint') {
            this.addPoint(rawData.data, rawData.shift, true);
        } else {
            this.removePoint(rawData.ref);
        }
    }
};


Rubix.ColumnSeries.prototype.globalRedraw = function(rubix) {
    // do nothing
};

Rubix.ColumnSeries.prototype.forceRedraw = function(rubix) {
    this.redraw(rubix);
};

Rubix.ColumnSeries.prototype.on_complete_draw = function() {
    if(!this.setup) return;
    var strokecolor = 'white';
    var self = this;
    if(this.opts.marker !== undefined && this.show_markers) {
        this.markers = this.rubix.symbols;

        this.markers.selectAll('.column-symbols').remove();
        var p = this.markers.selectAll('.column-symbols').data(this.layers, function(d) {
            return d.key;
        });

        var symbolGroup = p.enter().append('g');
        symbolGroup.attr('class', 'column-symbols');

        switch(this.opts.marker) {
            case 'circle':
            case 'cross':
            case 'square':
            case 'diamond':
            case 'triangle-up':
            case 'triangle-down':
                var symbolPath = symbolGroup.selectAll('path').data(function(d) {
                    for(var i=0; i<d.values.length; i++) {
                        d.values[i].marker = d.marker;
                        d.values[i].color = d.color;
                    }
                    return d.values;
                }).enter().append('path');
                symbolPath
                      .attr('d', function(d) {
                            var symbol = d3.svg.symbol();
                            symbol.type(d.marker);
                            return symbol();
                      })
                      .attr('fill', function(d) {
                            return d.color;
                      })
                      .style('display', function(d) {
                            if(self.rubix.column_offset === 'expand') {
                                if(d.y_new === 0 && d.y0 === 1) {
                                    return 'none';
                                }
                                return null;
                            }
                            if(d.y_new === null) {
                                return 'none';
                            }
                            return null;
                      })
                      .attr('stroke', strokecolor)
                      if(this.rubix.opts.invertAxes) {
                          symbolPath.attr('transform', function(d, i, j) {
                                var val = d.y0 + d.y_new;
                                if(isNaN(val)) {
                                    val = 0;
                                }
                                var _y = self.rubix.x(val);
                                if(self.grouped) {
                                    _y = self.rubix.x(d.y_new);
                                }
                                var _x = self.rubix.y(d.x) + self.rubix.y.rangeBand()/2;
                                if(self.grouped) {
                                    _x = (self.rubix.y(d.x) + (self.rubix.y.rangeBand()/self.layers.length) * j) + self.rubix.y.rangeBand()/(2*self.layers.length);
                                }
                                return 'translate('+_y+','+_x+')';
                          });
                      } else {
                          symbolPath.attr('transform', function(d, i, j) {
                                var val = d.y0 + d.y_new;
                                if(isNaN(val)) {
                                    val = 0;
                                }
                                var _y = self.rubix.y(val);
                                if(self.grouped) {
                                    _y = self.rubix.y(d.y_new);
                                }
                                var _x = self.rubix.x(d.x) + self.rubix.x.rangeBand()/2;
                                if(self.grouped) {
                                    _x = (self.rubix.x(d.x) + (self.rubix.x.rangeBand()/self.layers.length) * j) + self.rubix.x.rangeBand()/(2*self.layers.length);
                                }
                                return 'translate('+_x+','+_y+')';
                          });
                      }
                p.exit().remove();
            break;
            default:
                throw new Error('Unknown marker type : ' + this.opts.marker);
            break;
        }
    }
};

Rubix.ColumnSeries.prototype.setupFocus = function() {
    if(!this.setup) return;
    if(this.focus) this.focus.remove();
    this.focus = this.rubix.focus_group.append('g');
    this.focus.attr('class', 'focus');
    this.focus.style('display', 'none');
    var strokecolor = 'white';
    switch(this.opts.marker) {
        case 'circle':
        case 'cross':
        case 'square':
        case 'diamond':
        case 'triangle-up':
        case 'triangle-down':
            var symbol = d3.svg.symbol();
            symbol.type(this.opts.marker);
            this.focus.append('path').attr('d', symbol).attr('fill', this.opts.color).attr('stroke', strokecolor).attr('stroke-width', 2);
        break;
        default:
            throw new Error('Unknown marker type : ' + this.opts.marker);
        break;
    }
};

Rubix.ColumnSeries.prototype.on_focus = function(dx, dy) {
    if(!this.setup) return;
    this.off_focus();
    if(this.rubix.opts.invertAxes) {
        dy = dy.toString().replace('.', '\\\.');
        var rects = this.cb_series.selectAll('.column-'+dy);
    } else {
        dx = dx.toString().replace('.', '\\\.');
        var rects = this.cb_series.selectAll('.column-'+dx);
    }
    rects.classed('filled', true);
    rects.attr('fill-opacity', 1);
    rects.attr('stroke-width', 2);
};

Rubix.ColumnSeries.prototype.off_focus = function() {
    if(!this.setup) return;
    var rects = this.cb_series.selectAll('.filled');
    rects.classed('filled', false);
    rects.attr('fill-opacity', 0.85);
    rects.attr('stroke-width', 1);
};

window.Rubix = window.Rubix || {};

/**
 * @param {Rubix} rubix
 * @param {Object} opts
 * @constructor
 */
Rubix.AreaSeries = function(rubix, opts) {
    this.opts = opts;
    this.opts.color = this.opts.color || 'steelblue';
    this.opts.marker = this.opts.marker || 'circle';
    this.opts.fillopacity = this.opts.fillopacity || 0.5;
    this.opts.strokewidth = this.opts.strokewidth || 1;
    this.opts.noshadow = this.opts.noshadow || false;
    this.show_markers = this.opts.show_markers;

    if(!this.opts.hasOwnProperty('name')) throw new Error('AreaSeries should have a \'name\' property');

    this.name = this.opts.name;

    this.chart_hidden = false;
    this.temp_stack = [];

    this.setup = false;
    this.animating = false;

    this._setup(rubix);
};

/**
 * @param {Rubix} rubix
 * @private
 */
Rubix.AreaSeries.prototype._setup = function(rubix) {
    this.rubix = rubix;

    this.root   = this.rubix.root;
    this.data   = this.rubix.data;
    this.width  = this.rubix.width;
    this.height = this.rubix.height;
    this.area_series = this.rubix.root_area_series;
    this.show_markers = (this.show_markers === undefined) ? this.rubix.show_markers : this.show_markers;

    this.master_detail = this.rubix.master_detail;

    if(this.master_detail) {
        this.md_root = this.rubix.md_root;
    }

    if(!this.id) {
        this.id = this.rubix.uid('area');
    }

    /** separator */
    var self = this;
    this.line = d3.svg.line();
    this.line.defined(function(d) {
        return d.x !== null && d.y !== null;
    });
    this.line.x(function(d) {
        if(self.rubix.opts.invertAxes) {
            return self.rubix.x(d.y);
        }
        if(self.rubix.axis.x.range === 'column' || self.rubix.axis.x.range === 'bar') {
            return self.rubix.x(d.x) + self.rubix.x.rangeBand()/2;
        }
        return self.rubix.x(d.x);
    });
    this.line.y(function(d) {
        if(self.rubix.opts.invertAxes) {
            if(self.rubix.axis.y.range === 'column' || self.rubix.axis.y.range === 'bar') {
                return self.rubix.y(d.x) + self.rubix.y.rangeBand()/2;
            }
            return self.rubix.y(d.x);
        }
        return self.rubix.y(d.y);
    });
    this.line.interpolate(this.rubix.interpolate);

    this.area = d3.svg.area();
    this.area.defined(this.line.defined());
    if(this.rubix.opts.invertAxes) {
        this.area.x0(function(d) {
            return self.rubix.x(0);
        });
        this.area.x1(function(d) {
            return self.rubix.x(d.y);
        });
        this.area.y(function(d) {
            if(self.rubix.axis.y.range === 'column' || self.rubix.axis.y.range === 'bar') {
                return self.rubix.y(d.x) + self.rubix.y.rangeBand()/2;
            }
            return self.rubix.y(d.x);
        });
        this.area.interpolate(this.rubix.interpolate);
    } else {
        this.area.x(function(d) {
            if(self.rubix.axis.x.range === 'column' || self.rubix.axis.x.range === 'bar') {
                return self.rubix.x(d.x) + self.rubix.x.rangeBand()/2;
            }
            return self.rubix.x(d.x);
        });
        this.area.y0(function(d) {
            return self.rubix.y(0);
        });
        this.area.y1(function(d) {
            return self.rubix.y(d.y);
        });
        this.area.interpolate(this.rubix.interpolate);
    }

    if(this.master_detail) {
        this.master_line = d3.svg.line();
        this.master_line.defined(function(d) {
            return d.x !== null && d.y !== null;
        });
        this.master_line.x(function(d) {
            if(self.rubix.opts.invertAxes) {
                return self.rubix.x2(d.y);
            }
            return self.rubix.x2(d.x);
        });
        this.master_line.y(function(d) {
            if(self.rubix.opts.invertAxes) {
                return self.rubix.y2(d.x);
            }
            return self.rubix.y2(d.y);
        });
        this.master_line.interpolate(this.rubix.interpolate);

        this.master_area = d3.svg.area();
        this.master_area.defined(this.master_line.defined());
        this.area.interpolate(this.rubix.interpolate);
        if(this.rubix.opts.invertAxes) {
            this.master_area.x0(function(d) {
                return self.rubix.x2(0);
            });
            this.master_area.x1(function(d) {
                return self.rubix.x2(d.y);
            });
            this.master_area.y(function(d) {
                return self.rubix.y2(d.x);
            });
            this.master_area.interpolate(this.rubix.interpolate);
        } else {
            this.master_area.x(function(d) {
                return self.rubix.x2(d.x);
            });
            this.master_area.y0(function(d) {
                return self.rubix.y2(0);
            });
            this.master_area.y1(function(d) {
                return self.rubix.y2(d.y);
            });
            this.master_area.interpolate(this.rubix.interpolate);
        }
    }
};

// Alias for draw
Rubix.AreaSeries.prototype.redraw = function(rubix) {
    this._setup(rubix);
    this.draw();
};

Rubix.AreaSeries.prototype.noRedraw = function(rubix) {
    this._setup(rubix);
    this.draw(true);
};

/**
 * @param {Array|Object} data
 */
Rubix.AreaSeries.prototype.addData = function(data) {
    this.rubix.data_changed = true;
    if(!(data instanceof Array)) {
        if(!(data instanceof Object)) {
            throw new Error("Data must be an array or object");
        } else {
            if(!(data.hasOwnProperty('x') || data.hasOwnProperty('y'))) {
                throw new Error("Object must be in the form: {x: ..., y: ...}");
            }

            data = [data];
        }
    }

    if(!data.length) return;

    if(!this.rubix.opts.noSort) {
        data.sort(function(a, b) {
            if(a.x > b.x) {
                return 1;
            } else if(a.x === b.x) {
                return 0;
            } else {
                return -1;
            }
        });
    }

    this.data[this.name] = data;

    this.rubix.resetAxis(false);
    this.rubix.forceRedraw();
    this._animate_draw();
};

Rubix.AreaSeries.prototype.draw = function(forced) {
    if(!this.name) return;
    if(!this.data) return;
    if(!this.data.hasOwnProperty(this.name)) return;
    if(!this.data[this.name].length) return;
    var self = this;

    var isConstructed = this.area_series.selectAll('.'+this.id)[0].length;

    if(!isConstructed) {
        this.area_series.selectAll('.'+this.id+'.clipped').remove();
        if(!this.opts.noshadow) {
            this.strokePath1 = this.area_series.append('path').attr('class', this.id+' line').datum(this.data[this.name]).attr('d', this.line).attr('stroke', 'black').attr('fill', 'none').attr('stroke-linecap', 'round').attr('stroke-width', 5 * this.opts.strokewidth).attr('stroke-opacity', 0.05000000000000001).attr('transform', 'translate(1,1)');
            this.strokePath2 = this.area_series.append('path').attr('class', this.id+' line').datum(this.data[this.name]).attr('d', this.line).attr('stroke', 'black').attr('fill', 'none').attr('stroke-linecap', 'round').attr('stroke-width', 3 * this.opts.strokewidth).attr('stroke-opacity', 0.1).attr('transform', 'translate(1,1)');
            this.strokePath3 = this.area_series.append('path').attr('class', this.id+' line').datum(this.data[this.name]).attr('d', this.line).attr('stroke', 'black').attr('fill', 'none').attr('stroke-linecap', 'round').attr('stroke-width', 1 * this.opts.strokewidth).attr('stroke-opacity', 0.15000000000000002).attr('transform', 'translate(1,1)');
        }
        this.areaPath = this.area_series.append('path').attr('class', this.id+' area').datum(this.data[this.name]).attr('d', this.area).attr('fill', this.opts.color).attr('fill-opacity', this.opts.fillopacity).attr('stroke', 'none');
        this.linePath = this.area_series.append('path').datum(this.data[this.name]).attr('class', this.id+' line').attr('stroke', this.opts.color).attr('fill', 'none').attr('stroke-linecap', 'round').attr('d', this.line).attr('stroke-width', 2 * this.opts.strokewidth);

        if(this.master_detail) {
            this.md_root.select('.md-layers > .md_area_series').selectAll('.'+this.id+'.clipped').remove();
            this.masterLinePath = this.md_root.select('.md-layers > .md_area_series').append('path').datum(this.data[this.name]).attr('class', this.id+' line').attr('stroke', this.opts.color).attr('fill', 'none').attr('stroke-linecap', 'round').attr('d', this.master_line).attr('stroke-width', 2 * this.opts.strokewidth);
            this.masterLinePath.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip)');
            this.masterLinePath.classed('clipped', true);

            this.masterAreaPath = this.md_root.select('.md-layers > .md_area_series').append('path').attr('class', this.id+' area').datum(this.data[this.name]).attr('d', this.master_area).attr('fill', this.opts.color).attr('fill-opacity', this.opts.fillopacity).attr('stroke', 'none');
            this.masterAreaPath.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip)');
            this.masterAreaPath.classed('clipped', true);

            if(this.dataChanged) {
                this.rubix.resetExtent();
                this.dataChanged = false;
            }

            if(!this.opts.noshadow) {
                this.strokePath1.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip)');
                this.strokePath2.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip)');
                this.strokePath3.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip)');
            }
            this.linePath.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip)');

            if(!this.opts.noshadow) {
                this.strokePath1.classed('clipped', true);
                this.strokePath2.classed('clipped', true);
                this.strokePath3.classed('clipped', true);
            }
            this.linePath.classed('clipped', true);
            this.areaPath.classed('clipped', true);
        }
        this.areaPath.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip)');

        this.setup = true;
    } else {
        this.rubix.runCommand('globalRedraw');
    }

    this.rubix.resetFocus(forced);
};

/**
 * Alias for addPoint
 * @param {Object} data
 * @param {?Boolean} shift
 * @param {?Boolean} noRedraw
 */
Rubix.AreaSeries.prototype.updatePoint = function(data, shift, noRedraw) {
    this.addPoint(data, shift, noRedraw);
};

/**
 * @param {*} ref
 * @param {?Boolean} noRedraw
 */
Rubix.AreaSeries.prototype.removePoint = function(ref, noRedraw) {
    if(this.chart_hidden) {
        this.temp_stack.push({
            type: 'removePoint',
            ref: ref
        });
        return;
    }

    if(!this.data[this.name]) {
        this.data[this.name] = [];
    }

    var removed = false;
    for(var i=0; i<this.data[this.name].length; i++) {
        if(this.data[this.name][i].x === ref) {
            this.data[this.name].splice(i, 1);
            removed = true;
            break;
        }
    }

    if(this.master_detail) {
        this.dataChanged = true;
    }

    if(noRedraw) return;

    if(this.setup) {
        this._animate_draw();
    } else {
        this.rubix.resetAxis(true);
        this.rubix.forceRedraw();
    }
};

/**
 * @param {Object} data
 * @param {?Boolean} shift
 * @param {?Boolean} noRedraw
 */
Rubix.AreaSeries.prototype.addPoint = function(data, shift, noRedraw) {
    this.rubix.data_changed = true;
    if(!(data instanceof Object) || (data instanceof Array)) {
        throw new Error("Object required for addPoint");
    }
    if(!(data.hasOwnProperty('x') || data.hasOwnProperty('y'))) {
        throw new Error("Object must be in the form: {x: ..., y: ...}");
    }

    if(this.chart_hidden) {
        this.temp_stack.push({
            type: 'addPoint',
            data: data,
            shift: shift
        });
        return;
    }

    if(!this.data[this.name]) {
        this.data[this.name] = [];
    }

    var added = false;
    for(var i=0; i<this.data[this.name].length; i++) {
        if(this.data[this.name][i].x === data.x) {
            this.data[this.name][i].y = data.y;
            added = true;
            break;
        }
    }

    if(!added) {
        this.data[this.name].push(data);
    }

    this.data[this.name].sort(function(a, b) {
        if(a.x > b.x) {
            return 1;
        } else if(a.x === b.x) {
            return 0;
        } else {
            return -1;
        }
    });

    if(this.rubix.opts.interval) {
        if(this.rubix.opts.interval < this.data[this.name].length) {
            this.data[this.name].shift();
        }
    } else {
        if(shift) {
            this.data[this.name].shift();
        }
    }

    if(this.master_detail) {
        this.dataChanged = true;
    }

    if(noRedraw) return;
    if(this.setup) {
        this._animate_draw();
    } else {
        this.rubix.resetAxis(true);
        this.rubix.forceRedraw();
    }
};

Rubix.AreaSeries.prototype._animate_draw = function(local) {
    var self = this;
    var text = this.root.selectAll('.y.axis').selectAll('text')[0];
    var width = [];
    for(var i=0; i<text.length; i++) {
        width.push(text[i].getBBox().width);
    }
    var origMaxWidth = d3.max(width);

    this.rubix.resetAxis(false);

    text = this.root.selectAll('.y.axis').selectAll('text')[0];
    width = [];
    for(var i=0; i<text.length; i++) {
        width.push(text[i].getBBox().width);
    }

    var maxWidth = d3.max(width);
    this.rubix.marginLeft(maxWidth);
    this.rubix.resetFocus();
};

Rubix.AreaSeries.prototype.hidden = function() {
    this.chart_hidden = true;
};

Rubix.AreaSeries.prototype.show = function() {
    this.chart_hidden = false;
    while(this.temp_stack.length>1) {
        var rawData = this.temp_stack.shift();
        if(rawData.type === 'addPoint') {
            this.addPoint(rawData.data, rawData.shift, true);
        } else {
            this.removePoint(rawData.ref);
        }
    }
    if(this.temp_stack.length) {
        var rawData = this.temp_stack.shift();
        if(rawData.type === 'addPoint') {
            this.addPoint(rawData.data, rawData.shift, true);
        } else {
            this.removePoint(rawData.ref);
        }
    }
};

Rubix.AreaSeries.prototype.globalRedraw = function(rubix) {
    this.area_series.selectAll('.'+this.id+'.line').attr('d', this.line);
    this.area_series.selectAll('.'+this.id+'.area').attr('d', this.area);
};

Rubix.AreaSeries.prototype.forceRedraw = function(rubix) {
    this.redraw(rubix);
};

Rubix.AreaSeries.prototype.on_complete_draw = function() {
    if(!this.setup) return;
    var self = this;
    var datum = this.data[this.name];
    if(this.opts.marker !== undefined && this.show_markers) {
        if(this.master_detail) {
            this.rubix.symbols.attr('clip-path', 'url(#'+self.rubix.master_id+'-clip-symbols)');
        }
        this.markers = this.rubix.symbols.selectAll('.'+this.id+'-marker');
        switch(this.opts.marker) {
            case 'circle':
            case 'cross':
            case 'square':
            case 'diamond':
            case 'triangle-up':
            case 'triangle-down':
                var symbol = d3.svg.symbol();
                symbol.type(this.opts.marker);

                var symbolType = this.markers.data(datum);
                symbolType.enter().append('path')
                      .attr('d', symbol)
                      .attr('class', this.id + '-marker')
                      .attr('fill', this.opts.color)
                      .style('display', function(d) {
                            if(d.y === null) {
                                return 'none';
                            }
                            return null;
                      })
                      .attr('stroke', 'white')
                      .attr('transform', function(d) {
                            var val = d.y;
                            if(isNaN(val)) {
                                val = 0;
                            }
                            if(self.rubix.opts.invertAxes) {
                                var _y = self.rubix.y(d.x);
                                var _x = self.rubix.x(val);
                                if(self.rubix.axis.y.range === 'column' || self.rubix.axis.y.range === 'bar') {
                                    _y += self.rubix.y.rangeBand()/2;
                                }
                            } else {
                                var _y = self.rubix.y(val);
                                var _x = self.rubix.x(d.x);
                                if(self.rubix.axis.x.range === 'column' || self.rubix.axis.x.range === 'bar') {
                                    _x += self.rubix.x.rangeBand()/2;
                                }
                            }
                            return 'translate('+_x+','+_y+')';
                      });
                symbolType.exit().remove();
            break;
            default:
                throw new Error('Unknown marker type : ' + this.opts.marker);
            break;
        }
    }
};

Rubix.AreaSeries.prototype.setupFocus = function() {
    if(!this.setup) return;
    if(this.focus) this.focus.remove();
    this.focus = this.rubix.focus_group.append('g');
    this.focus.attr('class', 'focus');
    this.focus.style('display', 'none');
    switch(this.opts.marker) {
        case 'circle':
        case 'cross':
        case 'square':
        case 'diamond':
        case 'triangle-up':
        case 'triangle-down':
            var symbol = d3.svg.symbol();
            symbol.type(this.opts.marker);
            var path = this.focus.append('path').attr('d', symbol).attr('fill', this.opts.color).attr('stroke', 'white').attr('stroke-width', 2);
        break;
        default:
            throw new Error('Unknown marker type : ' + this.opts.marker);
        break;
    }
};

Rubix.AreaSeries.prototype.on_focus = function() {
    if(!this.setup) return;
    this.linePath.attr('stroke-width', 2 * this.opts.strokewidth);
};

Rubix.AreaSeries.prototype.off_focus = function() {
    if(!this.setup) return;
    this.linePath.attr('stroke-width', 2 * this.opts.strokewidth);
};

Rubix.Cleanup = function() {
    var master_id = null;
    for(var i=0; i<RubixListeners.length; i++) {
        master_id = RubixListeners[i];
        $(window).off('orientationchange.'+master_id);
        $(window).off('rubix.redraw.'+master_id);
        $(window).off('resize.rubix.'+master_id);
        $(window).off('debouncedresize.rubix.'+master_id);
        $(window).off('throttledresize.rubix.'+master_id);
    }
    RubixListeners = [];
};

Rubix.redraw = function() {
    $(window).trigger('resize');
};
