# WordPress Icon Manager

Init module with 'path' to directory with fonts (optional)
```
LA_IconManager::getInstance('path');
```

Bind `deleteOption` method to plugin deactivation (optional). That will delete all fonts from wp_options table and also will reload default fonts on next plugin activation
```
register_deactivation_hook( __FILE__, 'LA_IconManager::deleteOption' );
```

## Icon Manager field format 
Info about Set & Icon stored in one field with delimeter `_####_`
For Example `Font-Awesome_####_heart`

Get icon set name:
```
LA_IconManager::getSet('Font-Awesome_####_heart')
```
will return `Font-Awesome`

Get icon name:
```
LA_IconManager::getSet('Font-Awesome_####_heart')
```
will return `heart`

Get final icon CSS class:
```
LA_IconManager::getIconClass($string, $delimeter = '_####_', $prefix = 'la');
```
