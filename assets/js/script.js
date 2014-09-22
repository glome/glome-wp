var glome_scope = glome_scope || {};

(function($, scope) {

    console.log(':: loaded');
    console.log(scope);


    var ticker = (function () {

        var handle,
            tries = 30;

        return {

            check: (function(context) {

                var that = this;

                return function () {

                    if (tries <= 0) {
                        this.stop();
                        return;
                    }


                    tries -= 1;
                    jQuery.ajax(scope.pipe, {
                        data: {'action': 'verify'},
                        context: that
                    }).done(function (response){
                        if (response === '1') {
                            this.stop();
                            jQuery('#glome-qr').html('complete');
                        }
                    });
                }
            }),


            start: function () {

                handle = window.setInterval(this.check(this), 2000);
            },


            stop: function () {
                clearInterval(handle);
            }

        };

    }());

    $('#glome-login').on('click', function(){
        var data = {
            'action': 'challenge',
        };
        console.log(':: trigger');
        jQuery.get(scope.pipe, data, function(response) {
            var parts;
            parts = JSON.parse(response);
            $('#glome-qr').html(parts.join(' - '));
            ticker.start();
        });
    });


}(jQuery, glome_scope));


