requirejs.config({
    baseUrl: '/redirect_generator/frontend/lib/',
    urlArgs: "t=" + (new Date()).getTime()
});


// Start the main app logic.
requirejs(['jquery', 'jquery.form.min'],
    function($, form) {

        $(document).ready(function() {
            $('form').on('submit', function(e) {
                e.preventDefault();

                var options = {
                    success: show_response, 
                    url: '/redirect_generator/init.php',
                    type: 'post',
                    dataType: 'html'
                };

                form = $(this);
                form.ajaxSubmit(options);

            });
            $('#redirect, #canonical').on('click', function(e) {
                var val = $(this).val();
                $('input[data-type]').parent().hide();
                $('input[data-type*="'+val+'"]').parent().show();
                $('label:visible input[data-type]:first').click();
            });
 
            $('#redirect').click();
        });

        function show_response(responseText, statusText, xhr, $form) {
            if(responseText.length > 0) {
                $('#result textarea').html(responseText);
            } else {
                alert('Не выбран файл.');
            } 
        }
    }
);
