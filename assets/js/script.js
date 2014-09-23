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
                        that.stop();
                        return;
                    }


                    tries -= 1;
                    that.query.apply(that);
                }
            }),


            query: function () {
                jQuery.ajax(scope.pipe, {
                    data: {'action': 'verify'},
                    context: this,
                    cache: false
                }).done(this.verify);
            },

            verify: function (response){
                if (response === '1') {
                    this.stop();
                    jQuery('#glome-qr').html('complete');
                    window.location.reload();
                }
            },

            start: function () {
                handle = window.setInterval(this.check(this), 2000);
            },


            stop: function () {
                clearInterval(handle);
                tries = 30;
            }

        };

    }());

    $('#glome-login').on('click', function(){
        console.log(':: trigger');
        jQuery.ajax(scope.pipe, {
            data: {'action': 'challenge'},
            cache: false
        }).done(function(response) {
            var parts;
            if (response === '1') {
                window.location.reload();
                return;
            }
            parts = JSON.parse(response);
            $('#glome-qr').html(parts.join(' - '));
            ticker.start();
        });
    });


}(jQuery, glome_scope));


