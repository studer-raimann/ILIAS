(function($){
    class ErrorDefinition {
        constructor(start, length) {
            this.start = start;
            this.length = length;
        }
    }

    let process_error_text = function() {
        let text = $('#ete_error_text').val().split(' ');

        let errors = find_errors(text);

        if (errors.length > 0) {
            prepare_table(errors.length);
        }
        else {
            $('.aot_table_div').hide();
        }

        display_errors(errors, text);
    };

    let display_errors = function(errors, text) {
        $('.aot_table tbody').children().each(function (i, rrow) {
            let error = errors[i];
            let row = $(rrow);
            let label = text.slice(error.start, error.start + error.length).join(' ');
            label = label.replace('((', '').replace('))', '').replace('#', '');

            row.find('.etsd_wrong_text').text(label);
            row.find('#' + (i + 1) + '_answer_options_etsd_word_index').val(error.start);
            row.find('#' + (i + 1) + '_answer_options_etsd_word_length').val(error.length);
        });
    };

    let prepare_table = function(length) {
        $('.aot_table_div').show();
        let table = $('.aot_table tbody');
        let row = table.children().eq(0);

        row.siblings().remove();

        clear_row(row);

        while (length > table.children().length) {
            table.append(row.clone());
        }

        set_input_ids(table);
    };

    let find_errors = function(text) {
        let errors = [];

        let multiword = false;
        let multilength = 0;

        let i;
        for (i = 0; i < text.length; i += 1) {
            if (text[i].startsWith('#')) {
                errors.push(new ErrorDefinition(i, 1));
            }
            else if (text[i].startsWith('((')) {
                multiword = true;
                multilength = 0;
            }

            if (multiword) {
                multilength += 1;
            }

            if (multiword && text[i].endsWith('))')) {
                errors.push(new ErrorDefinition(i - (multilength - 1), multilength));
                multiword = false;
            }
        }

        return errors;
    };

    $(document).on('click', '#process_error_text', process_error_text);    
}(jQuery));