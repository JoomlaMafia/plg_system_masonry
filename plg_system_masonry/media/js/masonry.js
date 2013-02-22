/**
 * @copyright   Copyright (C) 2013 mktgexperts.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

//JHtml::_('behavior.framework', true);

window.addEvent('domready', function(){ 
	// vars
	var cols = 0;
	var objTimer = timer.periodical(1000);
	var count_state = "";
	// timer
	function timer() {
		// validation
		// TODO: Add debug mode
		if (!$$(brick_selector).length) {
			console.log("MKExp >> Masonry: Error, Required element not found"); // TODO: implement a method to avoid console flooding on dynamic mode 
			console.log("MKExp >> Selector: " + brick_selector);
			if (source_mode == "static") clearInterval(objTimer); 
			return;
		}
		if ($$(wall_selector).lengh) {
			console.log("MKExp >> Masonry: Error, Required element not found"); // TODO: implement a method to avoid console flooding on dynamic mode 
			console.log("MKExp >> Selector: " + wall_selector);
			if (source_mode == "static") clearInterval(objTimer);
			return;
		}
		// elements
		wall = $$(wall_selector)[0];
		bricks = $$(brick_selector);
		// set styles
		wall.setStyle("position", "relative");
		// measurements
		wall_width = parseInt(wall.getStyle("width"));
		wall_padding_left = parseInt(wall.getStyle("padding-left"));
		wall_padding_right = parseInt(wall.getStyle("padding-right"));
		wall_padding_top = parseInt(wall.getStyle("padding-top"));
		wall_padding_bottom = parseInt(wall.getStyle("padding-bottom"));
		wall_usable_width = wall_width - (0 + 0); // real usable horizontal space
		wall_usable_height = wall_width - (wall_padding_top + wall_padding_bottom); // real usable vertical space
		// calculating number of columns and brick width
		cols = Math.round(wall_usable_width/col_width);
		brick_width = wall_usable_width / cols;
		TolDif = (col_width * col_width_tolerance / 100);
		RangeL = col_width - (col_width - TolDif);
		RangeH = (col_width + TolDif) - col_width;
		if(cols < 2 ) {
			cols = 1;
			brick_width = wall_usable_width;
		}else if(RangeL < 0) {
			cols--;
			brick_width = wall_usable_width / cols;
		}else if(RangeH < 0) {
			cols++;
			brick_width = wall_usable_width / cols;
		}
		// arranging the bricks
		var cols_height = new Array(cols); 
		for (i=0; i < cols; i++) {
			cols_height[i] = 0;
		} // initialize the array with zeroes
		n = -1;
		bricks.each(function(brick) {
			// measurements
			brick_padding_left = parseInt(brick.getStyle("padding-left"));
			brick_padding_right= parseInt(brick.getStyle("padding-right"));
			brick_margin_left = parseInt(brick.getStyle("margin-left"));
			brick_margin_right = parseInt(brick.getStyle("margin-right"));
			border_left_width = parseInt(brick.getStyle("border-left-width"));
			border_right_width= parseInt(brick.getStyle("border-right-width"));
			brick_padding_top = parseInt(brick.getStyle("padding-top"));
			brick_padding_bottom = parseInt(brick.getStyle("padding-bottom"));
			brick_margin_top = parseInt(brick.getStyle("margin-top"));
			brick_margin_bottom = parseInt(brick.getStyle("margin-bottom"));
			border_top_width = parseInt(brick.getStyle("border-top-width"));
			border_bottom_width = parseInt(brick.getStyle("border-bottom-width"));
			brick_usable_width = brick_width - (brick_padding_left + brick_padding_right + brick_margin_left + brick_margin_right + border_left_width); // real usable horizontal space
			// lay bricks in order
			if (arrangement_mode == "inorder") {
				n++;
				// set styles
				brick.setStyles({
					position:'absolute',
					width:brick_usable_width + "px",
					left:((n * brick_width) + wall_padding_left) + "px",
					top:(cols_height[n] + wall_padding_top) + "px"
				});
				brick_height = parseInt(brick.getStyle("height"));
				brick_occupied_height = brick_height + (brick_padding_top + brick_padding_bottom + brick_margin_top + brick_margin_bottom + border_top_width + border_bottom_width); // real occupied vertical space
				cols_height[n] += brick_occupied_height;
				if (n = cols-1) n = -1;
			}
			// lay bricks with simple fit method
			if (arrangement_mode == "simplefit") {
				// find smallest column
				n = cols_height.indexOf(cols_height.min());
				// set styles
				// set styles
				brick.setStyles({
					position:'absolute',
					width:brick_usable_width + "px",
					left:((n * brick_width) + wall_padding_left) + "px",
					top:(cols_height[n] + wall_padding_top) + "px"
				});
				brick_height = parseInt(brick.getStyle("height"));
				brick_occupied_height = brick_height + (brick_padding_top + brick_padding_bottom + brick_margin_top + brick_margin_bottom + border_top_width + border_bottom_width); // real occupied vertical space
				cols_height[n] += brick_occupied_height;
			}
			// lay bricks with best fit method
			if (arrangement_mode == "bestfit") {
				// TODO: develop this mode
			}
			// lay bricks with jealous method
			if (arrangement_mode == "jealous") {
				// TODO: develop this mode
			}
		});
		
		// set wall height
		wall.setStyle("height", cols_height.max());
		
		$('infopanel').set("html",
			"wall_usable_width=" + wall_usable_width + "; " + "cols=" + cols + "; brick_width=" + brick_width + "; "
		);
	}

	// add brick
	var addbrick = new Element('button', {html: 'add brick'})
	addbrick.inject($$(wall_selector)[0], 'before');
	addbrick.addEvent('click', function(){
		(new Element('div', {
			style: "background: rgb(" + Number.random(0, 255) + "," + Number.random(0, 255) + "," + Number.random(0, 255) + "); position: absolute;",
			html: "lorem ipsum ".repeat(Number.random(1, 10)),
			class: "brick",
		})).inject($$(wall_selector)[0],'bottom');
		timer();
	})
	//Number.random(0, 255);

});












