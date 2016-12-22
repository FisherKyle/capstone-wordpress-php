LAIconManagerUtil = {
    getSet : function (string, delimeter) {
        delimeter = typeof delimeter === 'undefined' ? '_####_' : delimeter;

        var info = string.split(delimeter);
        if(info.length > 1){
            return info[0];
        }
        return false;
    },
    getIcon: function (string, delimeter) {
        delimeter = typeof delimeter === 'undefined' ? '_####_' : delimeter;

        var info = string.split(delimeter);
        if(info.length > 1){
            return info[1];
        }
        return false;
    },
    getIconClass: function (string, delimeter, prefix) {
        delimeter = typeof delimeter === 'undefined' ? '_####_' : delimeter;
        prefix = typeof prefix === 'undefined' ? 'la' : prefix;
        string = string.split('+').join(' ');

        var info = string.split(delimeter);
        if (info) {
            var set = info[0];
            return prefix + md5(set) + '-' + info[1];
        }
        return false;
    }
}