(function($){
    let has_tiny = false;
    
    let add_row = function() {
        has_tiny = tinymce.editors.length > 1;
    
        let row = $(this).parents(".aot_row").eq(0);
        let table = $(this).parents(".aot_table").children("tbody");
        
        if (has_tiny) {
            clear_tiny(table);
        }
    
        let new_row = row.clone();
    
        new_row = clear_row(new_row);
        row.after(new_row);
        set_input_ids(table);
    
        if (has_tiny) {
            tinymce.init(tinyMCE.EditorManager.editors[0].settings);
        }
    
        return false;
    };
    
    clear_tiny = function(table) {
        table.find('textarea, input[type=text]').each(function(index, item) {
            let element = $(item);
            if (!element.attr('id')) {
                return;
            }
            
            let editor = tinymce.get(element.attr('id'));
            if(!editor) {
                return;
            }
            
            has_tiny = true;
            
            element.val(editor.getContent());
            element.show();
    
            tinymce.EditorManager.remove(editor);
    
            element.siblings('.mceEditor').remove();
        });
    };
    
    let save_tiny = function() {
        let i;
        for (i = 0; i < tinymce.editors.length; i += 1) {
            let editor = tinymce.editors[i];
            let element = $(editor.getElement());
    
            element.val(editor.getContent());
        }
    };
    
    let remove_row = function() {
        has_tiny = tinymce.editors.length > 1;
    
        let row = $(this).parents(".aot_row");
        let table = $(this).parents(".aot_table").children("tbody");
        
        if (has_tiny) {
            clear_tiny(table);
        }
    
        if (table.children().length > 1) {
            row.remove();
            set_input_ids(table);
        } else {
            clear_row(row);
        }
    
        if (has_tiny) {
            tinymce.init(tinyMCE.EditorManager.editors[0].settings);
        }
    };
    
    clear_row = function(row) {
        row.find('input[type!="Button"], textarea').each(function() {
            let input = $(this);
    
            if (input.attr('type') === 'radio' ||
                    input.attr('type') === 'checkbox') {
                input.attr('checked', false);
            }
            else {
                input.val('');
            }
        });
    
        row.find('span').each(function() {
            let span = $(this);
            if (span.children().length === 0) {
                span.html('');
            }
        });
    
        return row;
    };
    
    let up_row = function() {
        let row = $(this).parents(".aot_row");
        row.prev('.aot_row').before(row);
        set_input_ids(row.parents(".aot_table").children("tbody"));
    };
    
    let down_row = function() {
        let row = $(this).parents(".aot_row");
        row.next('.aot_row').after(row);
        set_input_ids(row.parents(".aot_table").children("tbody"));
    };
    
    set_input_ids = function(table) {
        table.parent().siblings(".js_count").val(table.children().length);
    
        let current_row = 1;
    
        table.children().each(function() {
            process_row($(this), current_row);
            current_row += 1;
        });
    };
    
    let process_row = function(row, current_row) {
        row.find("input[name],textarea[name],select").each(function() {
            process_item($(this), current_row);
    
        });
    };
    
    let process_item = function(input, current_row) {
        let new_name = update_input_name(input.attr("name"), current_row);
    
        // if already an item with the new name exists
        // (when swapping) set the other element name
        // to current oldname to prevent name collision
        // and losing of radio values
        if (input.attr('type') === 'radio') {
            let existing_group = $('[name="' + new_name + '"]');
    
            if (existing_group.length > 0) {
                let my_name = input.attr("name");
                let my_group = $('[name="' + my_name + '"]');
                my_group.attr("name", "totally_random");
                existing_group.attr("name", my_name);
                my_group.attr("name", new_name);
            }
        }
        else {
            input.attr("name", new_name);
        }
    
        input.prop("id", update_input_name(input.prop("id"), current_row));    
    }
    
    let update_input_name = function(old_name, current_row) {
        return current_row + old_name.match(/\D.*/);
    };
    
    
    $(document).ready(function() {
        // hack to prevent image verification error
        $('[name=ilfilehash]').remove();
    });
    
    $(document).on("click", ".js_add", add_row);
    $(document).on("click", ".js_remove", remove_row);
    $(document).on("click", ".js_up", up_row);
    $(document).on("click", ".js_down", down_row);
    $(document).on("submit", "form", save_tiny);
}(jQuery));

//**********************************************************************************************
//Formula Authoring
//**********************************************************************************************

let var_regex = /\$(v|r)\d+/g;

let clear_table = function(selector) {
    let first_row = $(selector + ' .aot_row').eq(0);
    first_row.siblings().remove();
    clear_row(first_row);
};

let add_row_to = function(selector) {
    let first_row = $(selector + ' .aot_row').eq(0);
    first_row.after(first_row.clone());
};

let add_table_items = function () {
    clear_table('#il_prop_cont_fs_variables');
    clear_table('#il_prop_cont_answer_options');
    
    let variables = $('#question').val().match(var_regex);
    
    let vars = 0;
    let res = 0;
    
    variables.forEach(function(v) {
        if (v.charAt(1) === 'v') {
            vars += 1;
        }
        else {
            res += 1;
        }
    });
    
    for (vars; vars > 1; vars -= 1) {
        add_row_to('#il_prop_cont_fs_variables');
    }
    set_input_ids($('#il_prop_cont_fs_variables tbody'));
    
    for (res; res > 1; res -= 1) {
        add_row_to('#il_prop_cont_answer_options');
    }
    set_input_ids($('#il_prop_cont_answer_options tbody'));
};

$(document).on("click", ".js_parse_question", add_table_items);

//**********************************************************************************************
//Cloze Authoring
//**********************************************************************************************

let cloze_regex = /{[^}]*}/g;

let add_gap_items = function() {
    let last_non_gap = $('#il_prop_cont_cze_text');
    
    last_non_gap.nextUntil('.ilFormFooter').remove();
    
    let matches = $('#cze_text').val().match(cloze_regex);
    let gap_count = matches ? matches.length : 0;
    
    for (let i = 0; i < gap_count; i += 1) {
        last_non_gap.siblings('.ilFormFooter').before(create_new_text_gap(i + 1));
    }
}

let create_new_text_gap = function(i) {
    let template = $('.cloze_template .text').clone();
    
    template.find('select, input[type=hidden]').each(function(index, item) {
        process_item($(item), i);
    });
    
    $(template.find('.aot_table input')).each(function(index, item) {
        let input = $(item);
        input.prop("id", input.prop("id").replace('0', i));
        input.prop("name", input.prop("name").replace('0', i));
    });
    
    return template.children();
}

let create_new_number_gap = function(i) {
    let template = $('.cloze_template .number').clone();
    
    process_row(template, i);
    
    return template.children();
}

let change_gap_form = function(e) {
    selected = $(this);
    id = selected.prop('id').match(nr_regex);
    template = null;
    
    if (selected.val() === 'clz_number') {
        template = create_new_number_gap(id);
    }
    else {
        template = create_new_text_gap(id);
    }
    
    parent = selected.parents('.form-group');
    parent.nextUntil('.ilFormFooter, .ilFormHeader').remove();
    parent.after(template);
    parent.next().remove();
    parent.next().remove();
}

let nr_regex = /\d*/;

let prepare_form = function() {
    $('.cloze_template .ilFormFooter').remove();
    let template_forms = $('.cloze_template .form-horizontal');
    
    template_forms.each(function(index, item) {
        let form = $(item);
        form.parent().append(form.children());
        form.remove();        
    });
}

$(document).ready(prepare_form);

$(document).on("change", "select[id$=cze_gap_type]", change_gap_form);
$(document).on("click", ".js_parse_cloze_question", add_gap_items);
