function htmlEntities(str) {
    return String(str).replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"');
}
function generateBorderCss(string){
	var pairs = string.split('|');

	var result = {};
	pairs.forEach(function(pair) {
		pair = pair.split(':');
		result[pair[0]] = decodeURIComponent(pair[1]);
	});

	var cssCode1 = '', cssCode2 = '';
    cssCode1 += result.br_tl + 'px ' + result.br_tr + 'px ' + result.br_br + 'px ';
    cssCode1 += result.br_bl + 'px';
	var text = '';

	if(result.style !== 'none'){
	    text += 'border-style: ' + result.style +';';
	    text += 'border-color: ' + result.color +';';
	    text += 'border-top-width:'+ result.bw_t +'px;';
	    text += 'border-left-width:'+ result.bw_l +'px;';
	    text += 'border-right-width:'+ result.bw_r +'px;';
	    text += 'border-bottom-width:'+ result.bw_b +'px;';
	}
		text += 'border-radius: ' + cssCode1 +';';
	    text += '-moz-border-radius: ' + cssCode1+';';
	    text += '-webkit-border-radius: ' + cssCode1+';';

		return text;
}
function generate_and_apply_border_css( css_selector , border_string ){
	var pairs = border_string.split('|');

	var result = {};
	pairs.forEach(function(pair) {
		pair = pair.split(':');
		result[pair[0]] = decodeURIComponent(pair[1]);
	});

	if( result.br_type == 1 ){
		var border_radius = result.br_tl + 'px ' + result.br_tr + 'px ' + result.br_br + 'px ' + result.br_bl + 'px';
	}else{
		var border_radius = result.br_all + 'px ';
	}


	//	Border Radius
	if( cp_isValid( css_selector ) && jQuery( css_selector ).length ) {
		jQuery( css_selector ).css('border-radius', border_radius );
		jQuery( css_selector ).css('-moz-border-radius', border_radius );
		jQuery( css_selector ).css('-webkit-border-radius', border_radius );
	}

	//	Border
	if( cp_isValid( css_selector ) && jQuery( css_selector ).length && result.style!=='none') {
		jQuery( css_selector ).css('border-style', result.style );
		jQuery( css_selector ).css('border-color', result.color );
		if( result.bw_type == 1 ){
			jQuery( css_selector ).css('border-top-width', result.bw_t + 'px' );
			jQuery( css_selector ).css('border-left-width', result.bw_l + 'px' );
			jQuery( css_selector ).css('border-right-width', result.bw_r + 'px' );
			jQuery( css_selector ).css('border-bottom-width', result.bw_b + 'px' );
		}else{
			jQuery( css_selector ).css('border-width', result.bw_all + 'px' );
		}
	}
}

function generateBoxShadow(string){
	var pairs = string.split('|');
	var result = {};
	pairs.forEach(function(pair) {
		pair = pair.split(':');
		result[pair[0]] = decodeURIComponent(pair[1]);
	});

    res = '';
    if (result.type !== 'outset')
        res += result.type + ' ';

    res += result.horizontal + 'px ';
    res += result.vertical + 'px ';
    res += result.blur + 'px ';
    res += result.spread + 'px ';
    res += result.color;

	var style = 'box-shadow:'+res;

	if (result.type == 'none')
		style = '';

    return style+";";
}
function generate_and_apply_box_shadow_css( css_selector , box_shadow_string ){

	var pairs = box_shadow_string.split('|');
	var result = {};
	pairs.forEach(function(pair) {
		pair = pair.split(':');
		result[pair[0]] = decodeURIComponent(pair[1]);
	});

	var type = '';
	if( result.type == 'inset' )
		type = 'inset';

	res = '';

	if( result.type != 'none' ) {
		res += result.horizontal + 'px ';
		res += result.vertical + 'px ';
		res += result.blur + 'px ';
		res += result.spread + 'px ';
		res += result.color + ' ';
		res += type;
	}

	//	Border Radius
	if( jQuery( css_selector ).length ) {
		jQuery( css_selector ).css('box-shadow', res );
	}
}
