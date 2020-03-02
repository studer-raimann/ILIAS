(function($){
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

    let create_new_select_gap = function(i) {
        let template = $('.cloze_template .select').clone();
        
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
        else if (selected.val() === 'clz_text'){
            template = create_new_text_gap(id);
        }
        else if (selected.val() === 'clz_dropdown'){
            template = create_new_select_gap(id);
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
}(jQuery));