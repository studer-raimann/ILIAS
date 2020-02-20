(function($){
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
}(jQuery));