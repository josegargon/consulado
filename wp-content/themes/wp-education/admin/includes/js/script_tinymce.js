//FUNCTIONS
$.extend($.expr[":"], {
    "containsNC": function(elem, i, match, array) {
            return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
    }
});

var ButtonDialog = {
    local_ed : 'ed',

    init : function(ed) {
        ButtonDialog.local_ed = ed;
        tinyMCEPopup.resizeToInnerSize();
    },

    insert : function insertButton(ed) { 

        var scode = $('.shortcode_input').val();

        switch(scode) {
            case 'h1': shortcode_content = '[vz_heading] Heading 1 style text [/vz_heading]'; break;
            case 'h2': shortcode_content = '[vz_heading size="2"] Heading 2 style text [/vz_heading]'; break;
            case 'h3': shortcode_content = '[vz_heading size="3"] Heading 3 style text [/vz_heading]'; break;
            case 'h4': shortcode_content = '[vz_heading size="4"] Heading 4 style text [/vz_heading]'; break;
            case 'h5': shortcode_content = '[vz_heading size="5"] Heading 5 style text [/vz_heading]'; break;

            case 'list1': shortcode_content = '[vz_lists]  [vz_list] list1 content [/vz_list]  [vz_list] list2 content [/vz_list]  [/vz_lists]'; break;
            case 'list2': shortcode_content = '[vz_lists type="dots"]  [vz_list] list1 content [/vz_list]  [vz_list] list2 content [/vz_list]  [/vz_lists]'; break;
            case 'list3': shortcode_content = '[vz_lists type="arrow"]  [vz_list] list1 content [/vz_list]  [vz_list] list2 content [/vz_list]  [/vz_lists]'; break;

            case 'msg1': shortcode_content = '[vz_message] Just another message to users. [/vz_message]'; break;
            case 'msg2': shortcode_content = '[vz_message type="2"] Just another message to users. [/vz_message]'; break;
            case 'msg3': shortcode_content = '[vz_message type="3"] Just another message to users. [/vz_message]'; break;
            case 'msg4': shortcode_content = '[vz_message type="4"] Just another message to users. [/vz_message]'; break;

            case 'tab': shortcode_content = '[vz_tabs]  [vz_tab title="First tab"] first content [/vz_tab]  [vz_tab title="Second tab"] second content [/vz_tab] [/vz_tabs]'; break;

            case 'acc': shortcode_content = '[vz_accordions]  [vz_accordion title="First accordion"] first content [/vz_accordion]  [vz_accordion title="Second accordion"] second content [/vz_accordion] [/vz_accordions]'; break;

            case 'bq': shortcode_content = '[vz_blockquote author="Albert Einstein"] Everybody is a genius. But if you judge a fish by its ability to climb a tree, it will live its whole life believing that it is stupid. [/vz_blockquote]'; break;
        }
                                  
        // inserts the shortcode into the active editor
        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode_content);
        
        // closes Thickbox
        tinyMCEPopup.close();
    }
};

tinyMCEPopup.onInit.add(ButtonDialog.init, ButtonDialog);

$(function() {

    $("a.shortcode").click(function(event) {
        event.preventDefault();

        var the_scode = $(this).attr('id');

        $('.shortcode_input').val(the_scode);
        ButtonDialog.insert(ButtonDialog.local_ed);
    });

});