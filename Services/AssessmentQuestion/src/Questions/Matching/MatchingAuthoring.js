(function($){
    let MATCHING_ONE_TO_ONE = '0';
    let MATCHING_MANY_TO_ONE = '1';
    let MATCHING_MANY_TO_MANY = '2';
    let matching_mode;

    let used_terms = [];
    let used_definitions = [];

    let update_definitions = function() {
        update_values('me_definition_text', 'me_match_definition', used_definitions);
    };

    let update_terms = function() {
        update_values('me_term_text', 'me_match_term', used_terms);
    };

    let update_values = function(source, destination, useds) {
        let values = {};

        let i = 0;
        $('input[id$="' + source + '"]').each(function() {
            let val = $(this).val();

            if (!useds.includes(i.toString())) {
                values[i] = val;
            }

            i += 1;
        });

        $('select[id$="' + destination + '"]').each(function() {
            let that = $(this);
            let selected_val = that.val();
            let selected_text = that.children('option:selected').text();
            that.empty();

            Object.keys(values).forEach(function(key) {
                that.append(new Option(values[key], key));
            });

            if (useds.includes(selected_val)) {
                let found = false;

                that.children().each(function(index, child) {
                    let child_val = parseInt($(child).val());

                    if (child_val > parseInt(selected_val) && !found) {
                        $(child).before(new Option(selected_text, selected_val));
                        found = true;
                    }
                });

                if(!found) {
                    that.append(new Option(selected_text, selected_val));
                }
            }

            that.val(selected_val);
        });
    };

    let update_used_definitions = function() {
        if (matching_mode === MATCHING_ONE_TO_ONE) {
            update_used('me_match_definition', used_definitions);
        }
        else {
            used_definitions = [];
        }

        update_values('me_definition_text', 'me_match_definition', used_definitions);
    };

    let update_used_terms = function() {
        if (matching_mode === MATCHING_ONE_TO_ONE ||
            matching_mode === MATCHING_MANY_TO_ONE)
        {
            update_used('me_match_term', used_terms);
        } else {
            used_terms = [];
        }

        update_values('me_term_text', 'me_match_term', used_terms);
    };

    let update_used = function(selects, useds) {
        useds.splice(0, useds.length);

        $('select[id$="' + selects + '"]').each(function() {
            let val = $(this).val();
            if (val !== null) {
                useds.push(val);
            }
        });
    };

    let clean_added_row = function() {
        $('#il_prop_cont_me_matches').find('tr').last().find('select').each(function() {
            $(this).empty();
        });

        update_definitions();
        update_terms();
    };

    $(document).ready(function() {
        if ($('input[name=me_matching]').length > 0) {
            matching_mode = $('input[name=me_matching]:checked').val();
            update_used_definitions();
            update_used_terms();
        }
    });

    $(document).on('change', 'input[name=me_matching]', function() {
        matching_mode = $(this).val();
        update_used_definitions();
        update_used_terms();
    });

    $(document).on('change', 'input[id$="me_definition_text"]', update_definitions);
    $(document).on('change', 'input[id$="me_term_text"]', update_terms);
    $(document).on('change', 'select[id$=me_match_definition]', update_used_definitions);
    $(document).on('change', 'select[id$=me_match_term]', update_used_terms);
    
    //remove/add needs to trigger after remove event that actually removes the row
    $(document).on("click", "#il_prop_cont_me_matches .js_add", function() { setTimeout(clean_added_row, 1); });
    $(document).on('click', '#il_prop_cont_me_terms .js_remove', function() { setTimeout(update_terms, 1); });
    $(document).on('click', '#il_prop_cont_me_definitions .js_remove', function() { setTimeout(update_definitions, 1); });    
}(jQuery));