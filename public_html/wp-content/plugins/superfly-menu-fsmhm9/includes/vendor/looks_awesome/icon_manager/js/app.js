function LAIconManager(id, el, collection, field) {
    var self = this;
    this.ajax = new AwesomeAjax('laim');

    this.id = id;
    this.el = el;
    this.collection = collection || {};
    this.field = field || '';

    this.set = '';
    this.icon = '';

    this.bindSearch();
    this.bindUpload();
    this.bindDelete();
}

LAIconManager.prototype.bindField = function () {
    var self = this;
    var $ = jQuery;
    var dfd = $.Deferred();

    var handler = function () {
        if (self.field === '') {
            var set = '';
            var icon = '';
        } else {
            var set = $(self.field).val().split('_####_')[0];
            var icon = $(self.field).val().split('_####_')[1];
        }

        self.set = self.sanitize(set);
        self.icon = icon;

        setTimeout(function () {
            $(self.el).trigger('iconManagerIconChanged');
        }, 14);

        dfd.resolve();
    }

    handler();
    $(document).off('change', self.field);
    $(document).on('change', self.field, handler);

    return dfd.promise();
}

LAIconManager.prototype.bindCustomField = function () {
    var self = this;
    var $ = jQuery;
    var button = '[data-action="accept-rating-custom-icon-' + self.id + '"]';

    var handler = function (e) {
        e.preventDefault();

        var set = '####';
        var icon = $(self.custom_field).val().trim();

        if (icon === '') {
            return;
        }

        self.set = self.sanitize(set);
        self.icon = icon;

        $(self.field).val(set + '_####_' + icon).trigger('change');
        self.clearSelect();
    }

    $(document).off('click', button);
    $(document).on('click', button, handler);
}

LAIconManager.prototype.clearSelect = function () {
    var $ = jQuery;
    var $manager = $(this.el);
    $('li', $manager).attr('class', '');
}

LAIconManager.prototype.bindSelect = function () {
    var self = this;
    var $ = jQuery;
    var dfd = $.Deferred();

    $(document).off('.la_icon_manager', self.el + ' [data-action="change-icon"]');
    $(document).on('click.la_icon_manager', self.el + ' [data-action="change-icon"]', function (e) {
        var $manager = $(self.el);
        var set = $(this).parent('ul').data('set');
        var icon = $(this).data('icon');

        self.set = self.sanitize(set);
        self.icon = icon;

        $(self.field).val(set + '_####_' + icon).trigger('change');
        $(self.custom_field).val('');
        $('li', $manager).attr('class', '');
        $(this).addClass('active');
    });

    dfd.resolve();
    return dfd.promise();
}

LAIconManager.prototype.bindSearch = function () {
    var $ = jQuery;

    $(document).off('.la_icon_manager', "[name='icon_manager_search']");
    $(document).on("input.la_icon_manager", "[name='icon_manager_search']", function (e) {
        var manager = $(this).parents(".la-icon-manager").get(0);
        var sets = $(".icon-set", manager);
        var query = $(this).val().trim();
        $.each(sets, function (index, item) {
            var icons = $("li", item);
            var count = 0;
            icons.hide();
            $.each(icons, function (index, item) {
                var aliases = $(item).data("tags") || "";
                if (aliases.indexOf(query) !== -1) {
                    $(item).show();
                    count++;
                }
            });
            if (count === 0) {
                $(item).hide();
            }
            if (query.length === 0) {
                $(item).show();
                icons.show();
            }
        });
    });
}

LAIconManager.prototype.bindUpload = function () {
    var $ = jQuery;
    var self = this;

    $(document).off('.la_icon_manager', '[data-action="icon_manager_upload"]');
    $(document).on('click.la_icon_manager', '[data-action="icon_manager_upload"]', function (e) {
        e.preventDefault();
        var $notify = $('#la_icon_manager_notify'),
            $spinner = $('.upload_icons .spinner');

        if (typeof file_frame !== 'undefined') {
            file_frame.open();
            return;
        }

        file_frame = wp.media({
            frame: 'post',
            button: {
                text: "Load icons from Zip"
            },
            class: "media-frame",
            multiple: false,
            library: {
                type: 'application/octet-stream, application/zip'
            },
        });

        // handle image insert on media window submit
        file_frame.on('insert', function () {
            var json = file_frame.state().get('selection').first().toJSON();
            $spinner.css('visibility', 'visible');
            $notify.html('');

            if (0 > $.trim(json.url.length)) {
                return;
            }

            self.ajax.ajax({
                action: 'laim_upload_icons',
                data: {
                    url: json.url
                },
                success_handler: function (data) {
                    $spinner.css('visibility', 'hidden');
                    $notify.html('Icons uploaded successfully! Reloading the page... ').show();
                    setTimeout(function () {
                        window.location.reload();
                    }, 2000);
                },
                success_error_handler: function (errors) {
                    var html = '';
                    $.each(errors, function (index, value) {
                        html += '<p>';
                        html += value.message;
                        html += '</p>';
                    });
                    $spinner.css('visibility', 'hidden');
                    $notify.html(html).show();
                },
                error_handler: function (errors) {
                    $spinner.css('visibility', 'hidden');
                    $notify.html(errors).show();
                }
            });

            file_frame.close();
        });

        file_frame.open();
        return false;
    });
}

LAIconManager.prototype.bindDelete = function () {
    var $ = jQuery;
    var self = this;

    $(document).off('.la_icon_manager', '[data-action="icon_manager_delete"]:not(.bound)');
    $(document).on('click.la_icon_manager', '[data-action="icon_manager_delete"]:not(.bound)', function (e) {
        e.preventDefault();

        var font = $(this).data('font'),
            $notify = $('#la_icon_manager_notify'),
            $spinner = $('.spinner', $(this).parent('h4'));

        $spinner.css('visibility', 'visible');
        $notify.html('');

        self.ajax.ajax({
            action: 'laim_delete_icons',
            data: {
                font: font
            },
            success_handler: function (data) {
                $spinner.css('visibility', 'hidden');
                $notify.html('Icon pack deleted!').show();
                $('.icon-set-' + font.toLowerCase().replace_all(' ', '_'), $(self.el)).hide();
                if (window['la_icon_manager_collection'].contains(font)) {
                    var model = window['la_icon_manager_collection'].findWhere({'name': font});
                    window['la_icon_manager_collection'].remove(model);
                }

                setTimeout(function () {
                    $notify.html('');
                }, 2000);
            },
            success_error_handler: function (errors) {
                var html = '';
                $.each(errors, function (index, value) {
                    html += '<p>';
                    html += value.message;
                    html += '</p>';
                });
                $spinner.css('visibility', 'hidden');
                $notify.html(html).show();
            },
            error_handler: function (errors) {
                $spinner.css('visibility', 'hidden');
                $notify.html(errors).show();
            }
        });
    });
}

LAIconManager.prototype.bindPreview = function () {
    var $ = jQuery;
    var self = this;

    $(document).off('.la_icon_manager', this.el);
    $(document).on('iconManagerIconChanged.la_icon_manager', this.el, function () {
        var $preview = $('.preview', $(self.el));
        var icon;
        if (self.set === '####') {
            icon = '<i class="custom" style="background-image:url(' + self.icon + ')"></i>';
        } else {
            icon = '<i class="la' + md5(self.set) + '-' + self.icon + '"></i>';
        }
        $preview.html(icon)
    });
}

LAIconManager.prototype.showSearch = function () {
    var view = new LAIconManagerView({
        template: la_icon_manager_templates['search']
    });
    view.render();

    return this;
}

LAIconManager.prototype.getCollection = function (filter) {
    var collection = this.collection ? this.collection.clone() : [];
    var self = this;

    if (filter instanceof Array && filter.length > 0) {
        collection.reset();
        filter.forEach(function (item) {
            var model = self.collection.findWhere({name: item});
            collection.add(model);
        });
    }

    return collection.models;
}

LAIconManager.prototype.sanitize = function (val) {
    return val ? val.replace('+', ' ').trim() : val;
}

LAIconManager.prototype.getSet = function () {
    return this.set ? this.sanitize(this.set) : '';
}

LAIconManager.prototype.getIcon = function () {
    return this.icon ? this.icon : '';
}

LAIconManager.prototype.showIconSelect = function (filter, docs_url) {
    docs_url = typeof filter !== 'undefined' ? docs_url : '';
    filter = typeof filter !== 'undefined' ? filter : [];

    var $ = jQuery;
    var self = this;
    var $field = $(this.field);
    this.custom_field = '[name="la_icon_manager_' + self.id + '_custom"]';
    var collection = this.getCollection(filter);

    self.bindField()
    self.bindCustomField()

    var view = new LAIconManagerView({
        template: la_icon_manager_templates['select'],
        el: this.el,
        afterRender: function () {
            self.bindSelect().then(function () {
                self.bindPreview();
            });
        }
    });
    view.render({
        id: self.id,
        items: collection,
        $field: $field,
        docs_url: docs_url,
        library: false,
        current_set: self.getSet(),
        current_icon: self.getIcon()
    });

    return this;
}

LAIconManager.prototype.showLibrary = function (filter) {
    filter = typeof filter !== 'undefined' ? filter : [];
    var view = new LAIconManagerView({
        template: la_icon_manager_templates['library'],
        el: this.el
    });
    var collection = this.getCollection(filter);
    view.render({
        items: collection,
        library: true
    });

    return this;
}