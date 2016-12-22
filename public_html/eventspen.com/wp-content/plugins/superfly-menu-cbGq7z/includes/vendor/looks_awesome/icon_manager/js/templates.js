_.mixin({
    capitalize: function(string) {
        return string.charAt(0).toUpperCase() + string.substring(1).toLowerCase();
    }
});

window.la_icon_manager_templates = {
    search: '<div class="icon-manager-header">\
                <div class="preview"></div>\
                <input class="search" type="text" name="icon_manager_search" placeholder="Search icon in library">\
            </div>',
    upload: '<div class="upload_icons">\
                <input id="la_icon_manager_upload_icons" class="button-primary" data-action="icon_manager_upload" type="button" value="Upload New Icons" style="" />\
                <span class="spinner"></span>\
            </div>',
    sets: _.template(
        '<% _.each(items, function(item){ %>\
            <% print(la_icon_manager_templates.set({item: item.attributes, library: library, current_set: current_set, current_icon: current_icon})); %>\
        <% }); %>'),
    set: _.template(
        '<div class="icon-set icon-set-<%= item.name.toLowerCase().replace_all(\' \', \'_\') %>">\
            <div class="iconbox">\
                <h4 class="font_name">\
                    <strong><% print(_.capitalize(item.name)); %></strong>\
                    <span class="fonts-count count-<%= item.name %>">(<% print(item.icons.length) %> icons)</span>\
                    <% if(library) { %>\
                    <button class="button button-secondary button-small"\
                        data-action="icon_manager_delete"\
                        data-font="<% print(item.name) %>"\
                        data-title="Delete This Icon Set">Delete Icon Set</button>\
                    <% } %>\
                    <span class="spinner"></span>\
                </h4>\
                <div class="inside">\
                    <div class="a-scroll">\
                        <ul class="icons-list" data-set="<% print(item.name) %>">\
                            <% _.each(item.icons, function(icon){ %>\
                                <% var active = (current_set == item.name && current_icon == icon.class) ? "active" : ""; %>\
                                <% print(la_icon_manager_templates.icon({font: item.name, icon: icon, active: active})); %>\
                            <% }); %>\
                        </ul>\
                    </div>\
                </div>\
            </div>\
        </div>'),
    icon: _.template(
        '<li class="<%= active %>" title="<%= icon.class %>" data-icon="<%= icon.class %>" data-tags="<%= icon.tags %>" data-action="change-icon">\
            <i class="la<% print(md5(font)) %>-<%= icon.class %>"></i>\
            <label class="icon"><%= icon.class %></label>\
        </li>'),
    select: _.template(
        '<div class="la-icon-manager">\
            <div class="la_icon_manager_select" id="la_icon_manager_<%= id %>" data-field-name="<%= id %>">\
                <%= la_icon_manager_templates.search %>\
                <div class="icon-manager-body">\
                    <div class="icon-list a-scroll">\
                        <% print(la_icon_manager_templates.sets({items: items, library: library, current_set: current_set, current_icon: current_icon})); %>\
                    </div>\
                </div>\
                <div class="icon-manager-footer">\
                    <% var custom_icon = "";\
                    if(current_set === "####") {\
                        custom_icon = current_icon\
                    } %>\
                    <div class="form-group">\
                        <input class="custom-icon-source" type="text" name="la_icon_manager_<%= id %>_custom" placeholder="Or input base64 code" value="<%= custom_icon %>">\
                        <button class="btn button custom-icon-button" data-action="accept-rating-custom-icon-<%= id %>">Accept custom icon</button>\
                    </div>\
                    <% if(docs_url) { %>\
                        <p class="hint">\
                            <a target="_blank" href="<% docs_url %>" title="Read more about custom icon settings">\
                                Read more about custom icon settings\
                            </a>\
                        </p>\
                    <% } %>\
                </div>\
            </div>\
        </div>'),
    library: _.template(
        '<div class="la-icon-manager">\
            <%= la_icon_manager_templates.upload %>\
            <div id="la_icon_manager_notify" class="notify"></div>\
            <% print(la_icon_manager_templates.sets({items: items, library: library, current_set: "", current_icon: ""})); %>\
        </div>')
}