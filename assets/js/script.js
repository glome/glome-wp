var glome_scope = glome_scope || {};

(function($, scope) {

    console.log(':: loaded');
    console.log(scope);


    var data = {
        'action': 'challenge',
        'whatever': 'test'      // We pass php values differently!
    };

    $('#glome-login').on('click', function(){
        console.log(':: trigger');
        jQuery.get(scope.pipe, data, function(response) {
            var parts;
            parts = JSON.parse(response);
            $('#glome-qr').html(parts.join(' - '));
        });
    });


}(jQuery, glome_scope));


