!function ($) {
var $subnav;

    var selector ='[data-weekpicker]',
	
        all = [];

    function clearWeekPickers(except) {
        var ii;
        for (ii = 0; ii < all.length; ii++) {
            if (all[ii] != except) {
                all[ii].hide();
            }
        }
    }

    function WeekPicker(element, options) {
         this.$el = $(element); 		 
        this.proxy('show').proxy('ahead').proxy('hide').proxy('keyHandler').proxy('selectWeek');
        var options = $.extend({}, $.fn.weekpicker.defaults, options);
        if ((!!options.parse) || (!!options.format) || !this.detectNative()) {
            $.extend(this, options);
            this.$el.data('weekpicker', this);
            all.push(this);
            this.init();
        }
    }
    WeekPicker.prototype = {
        detectNative:function (el) {
            if (navigator.userAgent.match(/(iPad|iPhone); CPU(\ iPhone)? OS 5_\d/i)) {
                // jQuery will only change the input type of a detached element.
                var $marker = $('<span>').insertBefore(this.$el);				
                this.$el.detach().attr('type', 'date').insertAfter($marker);
                $marker.remove();
                return true;
            }
            return false;
        }, init:function () {
            this.calculateDates();
            $calendar = $("<div>").addClass('calendar');
            this.$months = $('<div>').addClass('months');
            var $nav = $('<div>').append(this.nav(this.numberOfMonths));
            // Populate day of week headers, realigned by startOfWeek.
            $head = $("<div>").addClass('head');
            for (var c = 0; c < 1; c++) {
                $head_col = $("<div>");
                for (var i = 0; i < this.shortDayNames.length; i++) {
                    $head_col.append('<div class="dow">' + this.shortDayNames[(i + this.startOfWeek) % 7] + '</div>');
                }
                $head.append($head_col.addClass('col' + c));
            }
            $calendar.append($head);
            $calendar.append(this.$months);
            this.$picker = $('<div>')
                .click(function (e) {
				
                    e.stopPropagation()
                })				
                // Use this to prevent accidental text selection.
                .mousedown(function (e) {
                    e.preventDefault()
                })
                .addClass('weekpicker')
                .append($nav, $calendar)
                .insertAfter(this.$el);

            this.$el
                .focus(this.show)
                .click(this.show)
                .change($.proxy(function () {
                this.selectWeek();								
            },  this));
			this.$el.parents('.input-append')
                .focus(this.show)
                .click(this.show)
                .change($.proxy(function () {
                this.selectWeek();								
            }, this)); 

            this.selectWeek();
            this.hide();
        }, proxy:function (meth) {
            // Bind a method so that it always gets the weekpicker instance for
            // ``this``. Return ``this`` so chaining calls works.
            this[meth] = $.proxy(this[meth], this);
            return this;
        }, daysBetween:function (start, end) {
            var start = Date.UTC(start.getFullYear(), start.getMonth(), start.getDate());
            var end = Date.UTC(end.getFullYear(), end.getMonth(), end.getDate());
            return (end - start) / 86400000;
        }, findClosest:function (dow, date, direction) {
            var difference = direction * (Math.abs(date.getDay() - dow - (direction * 7)) % 7);
            return new Date(date.getFullYear(), date.getMonth(), date.getDate() + difference);
        }, rangeStart:function (date) {
            return this.findClosest(this.startOfWeek,
                new Date(date.getFullYear(), date.getMonth()), -1);
        }, rangeEnd:function (date) {
            return this.findClosest((this.startOfWeek - 1) % 7,
                new Date(date.getFullYear(), date.getMonth() + 1, 0), 1);
        }, update:function (s) {
            this.$el.val(s).change();
			
        }, show:function (e) {
		
            e && e.stopPropagation();

            // Hide all other weekpickers.
            clearWeekPickers(this);
            var offset = this.$el.offset();
            this.$picker.css({
                top:offset.top + this.$el.outerHeight() + 2,
                left:offset.left
            }).show();

            $('html').on('keydown', this.keyHandler);
        }, hide:function () {
            this.$picker.attr("style","display:none");
            $('html').off('keydown', this.keyHandler);
        }, keyHandler:function (e) {
            // Keyboard navigation shortcuts.
            switch (e.keyCode) {
                case 9:
                case 27:
                case 13:
                    this.hide();
                    break;
                default:
                    return;
            }
            e.preventDefault();
        }, parse:function (s) {
            // Parse a partial RFC 3339 string into a Date.
            var m;
            if ((m = s.match(/^(\d{2,2})\/(\d{2,2})\/(\d{2,2})$/))) {
                return new Date(m[0], m[1] - 1, m[2]);
            } else {
                return null;
            }
        }, format:function (date) {
            // Format a Date into a string as specified by RFC 3339.
            var month = (date.getMonth() + 1).toString(),
                dom = date.getDate().toString();
            if (month.length === 1) {
                month = '0' + month;
            }
            if (dom.length === 1) {
                dom = '0' + dom;
            }
			return  dom + "/" + month + '/' + date.getFullYear().toString();
            //return month + "/" + dom + '/' + date.getFullYear().toString();
        }, formatWeek:function (from, to) {
            return this.format(from) + '-' + this.format(to);
        }, nav:function (months) {
		
            $subnav = $('<div style="text-align:center;margin-top: 8px;margin-bottom: 8px;margin-left: 8px;margin-right: 8px;">' +'<span class="prev pull-left"><i class="icon-arrow-left"/></span> ' +'<span id="check"></span>'+' <span class="next pull-right"><i class="icon-arrow-right"/></span>' +'</div>');
				 
            $('.prev', $subnav).click($.proxy(function () {
                this.ahead(-months)
            }, this));
            $('.next', $subnav).click($.proxy(function () {
                this.ahead(months)
            }, this));

            return $subnav;
        }, renderDays:function () {
            var dates = [
                [this.rangeStart(this.start), this.mid, 999],
                [this.mid_next, this.end, 999]
            ];

            var thisDay, prevMonth = -1, weekEnd;

            for (var c = 0; c < dates.length; c++) {
                dates[c][2] = this.daysBetween(dates[c][0], dates[c][1]);
				
                $col = $('<div>');
                $monthDays = $('<div>').addClass('month-weeks').css('border','none');

                var labels = [0, 0, 0, 0, 0], weeks = [0, 0, 0, 0, 0], m = -1, w = 0;
                for (var d = 0; d <= dates[c][2]; d++) {
				
                    thisDay = new Date(dates[c][0].getFullYear(), dates[c][0].getMonth(), dates[c][0].getDate() + d, 12, 00);

                    //Creating and append week in to month
                    if (this.startOfWeek == thisDay.getDay()) {
                        $week = $('<div>').addClass('week');						
                        weekEnd = new Date(thisDay.getFullYear(), thisDay.getMonth(), thisDay.getDate() + 4, 12, 00);
						 
                        $week.attr('date', this.formatWeek(thisDay, weekEnd));

                        firstDay = thisDay;
                        if (prevMonth != thisDay.getMonth()) {
                            m = m + 1;
                            labels[m] = thisDay;
                            prevMonth = thisDay.getMonth();
                        }
                        weeks[m] = weeks[m]+1;
						
                    }

                    //Append Day in to Week
                    $day = $('<div>').attr('date', this.format(thisDay));
                    $day.text(thisDay.getDate());					 					
                      //$day.addClass('box0');
					 //$day.removeClass('box' + labels[m]);					
                    $week.append($day);					
                    if ((d % 7 == 0)||(d % 7 == 1)||(d % 7 == 2)||(d % 7 == 3)||(d % 7 == 4)) {
					  $day.addClass('box0');
                        $monthDays.append($week);
                    }
					else if ((d % 7 == 5)||(d % 7 == 6))
					{
					$day.removeClass('box0');
					}
					if ((d % 7 == 0)) {
					  $day.addClass('box0').css('border-bottom-left-radius','4px');
					   $day.addClass('box0').css('border-top-left-radius','4px');
                        $monthDays.append($week);
                    }
					if ((d % 7 == 4)) {
					  $day.addClass('box0').css('border-bottom-right-radius','4px');
					   $day.addClass('box0').css('border-top-right-radius','4px');
                        $monthDays.append($week);
                    }
                }

                $monthLabels = $('<span>');
                var first = false, y, label;
                for (m = 0; m < weeks.length; m++) {
                    if (weeks[m] > 0) {
                        $label = $('<span style="text-align: center;font-weight: bold;color:black;">').text(" ");						
                        if (weeks[m] > 3) {
                            label = this.monthNames[labels[m].getMonth()]
							
                            if (y != labels[m].getFullYear()) {
                                y = labels[m].getFullYear();
                                label += y.toString();
                            }
                            $label.text(label);
                            if (!first) {
                                first = true;
                                //$label.addClass('first');
                            }
                        }
                        if (weeks[m + 1] <= 0) {
                           // $label.addClass('last');
                        }
                        $monthLabels.append($label);
                    }
                }											
              $("#check").append($monthLabels);
			   
                $col.append($monthDays);

                this.$months.append($col);
            }
        }, renderMonth:function (week) {
		   $("#check").empty();
            this.$months.empty();
            this.renderDays();
 
            $('.week',this.$months).click($.proxy(function (e) {
			
                this.update($(e.target).parent().attr("date"));
				this.$picker.hide();
				
            }, this)).hover(function (e) {
                    $('.highlighted', this.$months).removeClass('highlighted');
                    $(this).addClass('highlighted');
                });
            $('.selected', this.$months).removeClass('selected');
            $('[date="' + week + '"]', this.$months).addClass('selected');
        }, ahead:function (months) {
            var date = new Date(this.start.getFullYear(), this.start.getMonth(), 1);
            date = new Date(date.getFullYear(), date.getMonth() + months, 1);
            this.calculateDates(date);
            this.selectWeek();
        }, calculateDates:function (date) {
            this.start = date ? date : new Date();
            this.mid = this.rangeEnd(new Date(this.start.getFullYear(), this.start.getMonth() + Math.ceil(this.numberOfMonths / 2), 0));
            this.mid_next = new Date(this.mid.getFullYear(), this.mid.getMonth(), this.mid.getDate() + 1);
            this.end = this.rangeEnd(new Date(this.start.getFullYear(), this.start.getMonth() + this.numberOfMonths, 0));
        }, selectWeek:function (week) {		
            if (typeof(week) == "undefined") {			
                week = this.$el.val();			
            }
            this.renderMonth(week);
        }
    };

    /* WEEKPICKER PLUGIN DEFINITION
     * ============================ */

    $.fn.weekpicker = function (options) {
        return this.each(function () {
            new WeekPicker(this, options);
			
        });
    };

    $(function () {
        $(selector).weekpicker();
        $('html').click(clearWeekPickers);
    });

    $.fn.weekpicker.WeekPicker = WeekPicker;

    $.fn.weekpicker.defaults = {
        monthNames:["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        shortDayNames:["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
		
        startOfWeek:1,
        numberOfMonths:1
    };
}(window.jQuery || window.ender);
