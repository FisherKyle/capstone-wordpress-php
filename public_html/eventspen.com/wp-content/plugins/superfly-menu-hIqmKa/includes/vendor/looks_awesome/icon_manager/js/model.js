var LAIconManagerModel = Backbone.Model.extend({
    defaults: {
        icons: []
    },
    initialize: function () {

    }

});

var LAIconManagerCollection = Backbone.Collection.extend({
    initialize: function () {
        this._meta = {};
    },
    model: LAIconManagerModel,
    meta: function (prop, value) {
        if(value === undefined){
            return this._meta[prop]
        }else{
            this._meta[prop] = value;
        }
    }
});