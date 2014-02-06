/**
 * tablePagination - A table plugin for jQuery that creates pagination elements
 *
 * http://neoalchemy.org/tablePagination.html
 *
 * Copyright (c) 2009 Ryan Zielke (neoalchemy.com)
 * licensed under the MIT licenses:
 * http://www.opensource.org/licenses/mit-license.php
 *
 * @name tablePagination
 * @type jQuery
 * @param Object settings;
 *      firstArrow - Image - Pass in an image to replace default image. Default: (new Image()).src="./images/first.gif"
 *      prevArrow - Image - Pass in an image to replace default image. Default: (new Image()).src="./images/prev.gif"
 *      lastArrow - Image - Pass in an image to replace default image. Default: (new Image()).src="./images/last.gif"
 *      nextArrow - Image - Pass in an image to replace default image. Default: (new Image()).src="./images/next.gif"
 *      rowsPerPage - Number - used to determine the starting rows per page. Default: 5
 *      currPage - Number - This is to determine what the starting current page is. Default: 1
 *      optionsForRows - Array - This is to set the values on the rows per page. Default: [5,10,25,50,100]
 *      ignoreRows - Array - This is to specify which 'tr' rows to ignore. It is recommended that you have those rows be invisible as they will mess with page counts. Default: []
 *      topNav - Boolean - This specifies the desire to have the navigation be a top nav bar
 *
 *
 * @author Ryan Zielke (neoalchemy.org)
 * @version 0.5
 * @requires jQuery v1.2.3 or above
 */

 (function($){

	$.fn.tablePagination = function(settings) {
		var defaults = {  
			firstArrow : (new Image()).src="./img/first.gif",  
			prevArrow : (new Image()).src="./img/prev.gif",
			lastArrow : (new Image()).src="./img/last.gif",
			nextArrow : (new Image()).src="./img/next.gif",
			rowsPerPage : 10,
			currPage : 1,
			optionsForRows : [2,5,10,25,50,100],
			ignoreRows : [],
			topNav : false
		};  
		settings = $.extend(defaults, settings);
		
		return this.each(function() {
      var table = $(this)[0];
      var totalPagesId, currPageId, rowsPerPageId, firstPageId, prevPageId, nextPageId, lastPageId,pagePageId1,pagePageId2,pagePageId3,tblPagination;
      totalPagesId = '#tablePagination_totalPages';
      currPageId = '#tablePagination_currPage';
      firstPageId = '#tablePagination_firstPage';
      prevPageId = '#tablePagination_prevPage';
      nextPageId = '#tablePagination_nextPage';
      lastPageId = '#tablePagination_lastPage';
      pagePageId1 = '#tablePagination_Page1';
      pagePageId2 = '#tablePagination_Page2';
      pagePageId3 = '#tablePagination_Page3';
      tblPagination  = '#tablePagination';
      hdnCurrPageNumber ='#currPageNumber';
      var tblLocation = (defaults.topNav) ? "prev" : "next";

      var possibleTableRows = $.makeArray($('tbody tr', table));
      var tableRows = $.grep(possibleTableRows, function(value, index) {
        return ($.inArray(value, defaults.ignoreRows) == -1);
      }, false)
      
      var numRows = tableRows.length
      var totalPages = resetTotalPages();
      var currPageNumber = (defaults.currPage > totalPages) ? 1 : defaults.currPage;
      var firstPageNumber = currPageNumber;
      var secondPageNumber = currPageNumber +1;
      var thirdPageNumber = currPageNumber +2;
      if ($.inArray(defaults.rowsPerPage, defaults.optionsForRows) == -1)
        defaults.optionsForRows.push(defaults.rowsPerPage);
      
      
      function hideOtherPages(pageNum) {
        if (pageNum==0 || pageNum > totalPages)
          return;
        var startIndex = (pageNum - 1) * defaults.rowsPerPage;
        var endIndex = (startIndex + defaults.rowsPerPage - 1);
        $(tableRows).show();
        for (var i=0;i<tableRows.length;i++) {
          if (i < startIndex || i > endIndex) {
            $(tableRows[i]).hide()
          }
        }
      }
      
      function resetTotalPages() {
        var preTotalPages = Math.round(numRows / defaults.rowsPerPage);
        var totalPages = (preTotalPages * defaults.rowsPerPage < numRows) ? preTotalPages + 1 : preTotalPages;
        if ($(table)[tblLocation]().find(totalPagesId).length > 0)
          $(table)[tblLocation]().find(totalPagesId).html(totalPages);
        return totalPages;
      }
      
      function resetCurrentPage(currPageNum) {
        if (currPageNum < 1 || currPageNum > totalPages)
          return;
        currPageNumber = currPageNum;
		//alert(currPageNumber);
        hideOtherPages(currPageNumber);
        switch (parseInt(currPageNumber)) {
        case 1:
        	firstPageNumber = currPageNumber;
        	secondPageNumber = currPageNumber+1;
        	thirdPageNumber = currPageNumber +2;
        	$(table)[tblLocation]().find(pagePageId1).html(currPageNumber).parent().addClass('active');
        	$(table)[tblLocation]().find(pagePageId2).html(currPageNumber + 1).parent().removeClass('active');
        	$(table)[tblLocation]().find(pagePageId3).html(currPageNumber+2).parent().removeClass('active');
        	$(table)[tblLocation]().find(nextPageId).parent().removeClass('disabled');
        	$(table)[tblLocation]().find(prevPageId).parent().addClass('disabled');
        	$(table)[tblLocation]().find(tblPagination).focus();  
        	$(window).scrollTop($(document).height());
        	break;
        case totalPages:
        	if(totalPages == 2) {
        		firstPageNumber = currPageNumber -1;
            	secondPageNumber = currPageNumber;
            	$(table)[tblLocation]().find(pagePageId1).html(currPageNumber-1).parent().removeClass('active').focus();;
            	$(table)[tblLocation]().find(pagePageId2).html(currPageNumber).parent().addClass('active');
        	} else {
	        	firstPageNumber = currPageNumber -2;
	        	secondPageNumber = currPageNumber-1;
	        	thirdPageNumber = currPageNumber;
	        	$(table)[tblLocation]().find(pagePageId1).html(currPageNumber-2).parent().removeClass('active').focus();;
	        	$(table)[tblLocation]().find(pagePageId2).html(currPageNumber -1).parent().removeClass('active');
	        	$(table)[tblLocation]().find(pagePageId3).html(currPageNumber).parent().addClass('active');
        	}
        	$(table)[tblLocation]().find(nextPageId).parent().addClass('disabled');
        	$(table)[tblLocation]().find(prevPageId).parent().removeClass('disabled');
        	$(table)[tblLocation]().find(tblPagination).focus();
        	$(window).scrollTop($(document).height());
        	break;
        default: 
        	firstPageNumber = currPageNumber -1;
    		secondPageNumber = currPageNumber;
    		thirdPageNumber = currPageNumber +1;
    		$(table)[tblLocation]().find(pagePageId1).html(currPageNumber -1).parent().removeClass('active');
    		$(table)[tblLocation]().find(pagePageId2).html(currPageNumber).parent().addClass('active');
    		$(table)[tblLocation]().find(pagePageId3).html(currPageNumber+1).parent().removeClass('active');
    		$(table)[tblLocation]().find(nextPageId).parent().removeClass('disabled');
        	$(table)[tblLocation]().find(prevPageId).parent().removeClass('disabled');
        	$(table)[tblLocation]().find(tblPagination).focus();
        	$(window).scrollTop($(document).height());
        	break;
        }
        $(table)[tblLocation]().find(hdnCurrPageNumber).val(currPageNumber);
		
        return false;
      }
      
      function createPaginationElements() {
        var htmlBuffer = [];
		//US 312 item 9,10 pagination margins altered..reflects in many pages
        htmlBuffer.push("<div id='tablePagination' name='tablePagination' class='pagination' align='right' style=' margin-top: 0px; margin-bottom: 10px; '><input type='hidden' name='currPageNumber' id='currPageNumber' value='" + firstPageNumber + "' />");
        htmlBuffer.push("<ul>");
        var prevDisabled =(firstPageNumber == 1) ? "class='disabled'" : "";
        htmlBuffer.push("<li " + prevDisabled + "><a href='javascript:void(0);' id='tablePagination_prevPage' >Prev</a></li>");
        var firstActive ='', secondActive='',thirdActive='';
        switch(currPageNumber) {
        case 1:
        	firstPageNumber =1;
        	secondPageNumber=2;
        	thirdPageNumber=3;
        	firstActive ="class='active'";
        	break;
        case 2:
        	firstPageNumber = 1;
        	secondPageNumber=2;
        	thirdPageNumber=3;
        	secondActive ="class='active'";
        	break;
        case totalPages:
        		firstPageNumber = currPageNumber-2;
    			secondPageNumber=currPageNumber-1;
    			thirdPageNumber=currPageNumber;
    			thirdActive ="class='active'";
        	break;
        	default:
        		firstPageNumber = currPageNumber-1;
        		secondPageNumber=currPageNumber;
        		thirdPageNumber=currPageNumber+1;
        		secondActive ="class='active'";
        	break;
        }
        
        htmlBuffer.push("<li " + firstActive +"><a href='javascript:void(0);' id='tablePagination_Page1'>" + firstPageNumber +"</a></li>");
        htmlBuffer.push("<li " + secondActive + "><a href='javascript:void(0);' id='tablePagination_Page2'>" + secondPageNumber +"</a></li>");
        if(totalPages >= 3)
        	htmlBuffer.push("<li " + thirdActive + "><a href='javascript:void(0);' id='tablePagination_Page3'>" + thirdPageNumber +"</a></li>");
        var nextDisabled =(currPageNumber == totalPages) ? "class='disabled'" : "";
        
        htmlBuffer.push("<li " + nextDisabled +"><a href='javascript:void(0);' id='tablePagination_nextPage'>Next</a></li>");
        htmlBuffer.push("</ul>");        
        htmlBuffer.push("</div>");
        return htmlBuffer.join("").toString();
      }
      
      if ($(table)[tblLocation]().find(totalPagesId).length == 0) {
		if (defaults.topNav) {
			$(this).before(createPaginationElements());
		} else {
			$(this).after(createPaginationElements());
		}
      }
      else {
        $(table)[tblLocation]().find(currPageId).val(currPageNumber);
      }
      hideOtherPages(currPageNumber);
      $(table)[tblLocation]().find(firstPageId).bind('click', function (e) {
        resetCurrentPage(1)
      });
      $(table)[tblLocation]().find(prevPageId).bind('click', function (e) {
        resetCurrentPage(currPageNumber - 1);
        return false;
      });
      $(table)[tblLocation]().find(nextPageId).bind('click', function (e) {
        resetCurrentPage(parseInt(currPageNumber) + 1)
        return false;
      });
      $(table)[tblLocation]().find(pagePageId1).bind('click', function (e) {
          resetCurrentPage(parseInt(firstPageNumber))
          return false;
        });
      $(table)[tblLocation]().find(pagePageId2).bind('click', function (e) {
          resetCurrentPage(parseInt(secondPageNumber) )
          return false;
        });
      $(table)[tblLocation]().find(pagePageId3).bind('click', function (e) {
          resetCurrentPage(parseInt(thirdPageNumber) )
          return false;
        });
      $(table)[tblLocation]().find(lastPageId).bind('click', function (e) {
        resetCurrentPage(totalPages)
      });
      
      $(table)[tblLocation]().find(currPageId).bind('change', function (e) {
        resetCurrentPage(this.value)
      });
		})
	};		
})(jQuery);