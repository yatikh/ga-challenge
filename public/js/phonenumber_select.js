var $form = $('.j-purchasing'),
    $select = $form.find('select');

if (!$select.children()) {
    $.getJSON(
        'twilio/phonenumbers/' + $form.data('country'),
        [],
        function (response) {
            if (response.items) {
                for (var ind in response.items) {
                    $select.append('<option value="' + response.items[ind] + '">' + response.items[ind] + '</option>');
                }
            }
        }
    );
}
