LAIconManagerView = Backbone.View.extend({
    initialize: function (data) {
        this.template = data.template;
        this.$el = jQuery(data.el);

        this.on('render', data.afterRender || this.afterRander);
    },
    afterRander: function () {

    },
    render: function (data) {
        this.$el.empty();
        this.$el.append(this.template(data));
        this.trigger('render');
        return this;
    },
});