(function() {  
    
    tinymce.create('tinymce.plugins.shortcodes', {

        init : function(ed, url) {              

            ed.addButton('shortcodes', {  
                title : 'Use vuzzu shortcodes',  
                image : url.replace('/js','')+'/images/shortcodes.png',  
                cmd : 'shortcodes',
                pluginurl : ajaxurl                
            });

            ed.addCommand('shortcodes', function() {
                ed.windowManager.open({
                    file : url.replace('/js','')+'../../tinymce_shortcode.php',
                    width : 500 + parseInt(ed.getLang('button.delta_width', 0)),
                    height : 600 + parseInt(ed.getLang('button.delta_height', 0)),
                    inline : 1
                }, {
                    plugin_url : url
                });
            });

        }

    });

    tinymce.PluginManager.add('shortcodes', tinymce.plugins.shortcodes);

}
)();

jQuery.urlParam = function(url,name) {
    var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(url);
    if (results == null){return ''}
    return results[1] || 0;
}