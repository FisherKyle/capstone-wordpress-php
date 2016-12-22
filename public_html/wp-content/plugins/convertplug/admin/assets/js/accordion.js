(function( $ ) {
    jQuery.widget("metro.accordion", {
        version: "1.0.0",
        options: {
            closeAny: true,
            open: function(frame){},
            action: function(frame){}
        },
        _frames: {},
        _create: function(){
            var element = this.element;
            if (element.data('closeany') != undefined) this.options.closeAny = element.data('closeany');
            this.init();
        },
        init: function(){
            var that = this;
            that.element.on('click', '.accordion-frame > .heading', function(e){
                e.preventDefault();
                e.stopPropagation();
                if (jQuery(this).attr('disabled') || jQuery(this).data('action') == 'none') return;
                if (that.options.closeAny) that._closeFrames();
                var frame = jQuery(this).parent(), content = frame.children('.content');
                //console.log(this);
                if (jQuery(content).is(":hidden")) {
                    jQuery(content).slideDown();
                    jQuery(this).removeClass("collapsed");
                    that._trigger("frame", e, {frame: frame});
                    that.options.open(frame);
                } else {
                    jQuery(content).slideUp();
                    jQuery(this).addClass("collapsed");
                }
                that.options.action(frame);
            });
            var frames = this.element.children('.accordion-frame');
            frames.each(function(){
                var frame = this,
                    a = jQuery(this).children(".heading"),
                    content = jQuery(this).children(".content");
                if (jQuery(frame).hasClass("active") && !jQuery(frame).attr('disabled') && jQuery(frame).data('action') != 'none') {
                    jQuery(content).show();
                    jQuery(a).removeClass("collapsed");
                } else {
                    jQuery(a).addClass("collapsed");
                }
            });
        },
        _closeFrames: function(){
            var frames = this.element.find('.accordion-frame');
            jQuery.each(frames, function(){
                var frame = jQuery(this);
                frame.find('.heading').addClass('collapsed');
                frame.find('.content').slideUp();
            });
			//console.log(this.element);
            //this._frames.children(".content").slideUp().parent().children('.heading').addClass("collapsed");
        },
        _destroy: function(){},
        _setOption: function(key, value){
            this._super('_setOption', key, value);
        }
    })
})( jQuery );
