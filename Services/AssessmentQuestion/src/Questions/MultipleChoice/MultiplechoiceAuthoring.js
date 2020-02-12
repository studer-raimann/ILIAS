(function($){
    let image_header = '';
    
    let show_multiline_editor = function() {
        
        //wait for tiny to load
        if (tinyMCE.EditorManager.editors.length < 1) {
            setTimeout(show_multiline_editor, 250);
            return;
        }
        
        let tiny_settings = tinyMCE.EditorManager.editors[0].settings;
        tiny_settings.mode = '';
        tiny_settings.selector = 'input[id$=mcdd_text]';
        tinymce.init(tiny_settings);
        
        $('input[id$=mcdd_image]').each(function(index, item) {
            let td = $(item).parents('td');
            td.children().hide();
            
            if (image_header.length == 0) {
                let th = td.closest('table').find('th').eq(td.index())[0];
                image_header = th.innerHTML;
                th.innerHTML = '';
            }
        });
    }
    
    let hide_multiline_editor = function() {
        clear_tiny($('.aot_table tbody'));
        
        $('input[id$=mcdd_image').each(function(index, item) {
            let td = $(item).parents('td');
            td.children().show();      
            
            if (image_header.length > 0) {
                let th = td.closest('table').find('th').eq(td.index())[0];
                th.innerHTML = image_header; 
                image_header = '';
                           
            }
        });
    }
    
    let update_editor = function() {
        if ($('#singleline').val() == 'true') {
            hide_multiline_editor();
        }
        else {
            show_multiline_editor();
        }
    }
    
    $(window).load(function() {
        if ($('#singleline').length > 0) {
            update_editor();
        }
    });
    
    $(document).on('change', '#singleline', update_editor);
}(jQuery));