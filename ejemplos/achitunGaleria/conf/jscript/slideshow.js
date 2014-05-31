(function($){
    $.fn.slideshow = function(interval) {
        var slides;
        var cnt;
        var amount;
        var i;

        function run() {
            // hiding previous image and showing next
            $(slides[i]).fadeOut(1000);
            i++;
            if (i >= amount) i = 0;
            $(slides[i]).fadeIn(1000);

            // updating counter
            cnt.text(i+1+' / '+amount);

            // loop
            setTimeout(run, interval);
        }
        slides = $(this).find('ul').children();
        cnt = $(this).find('.counter');
        amount = slides.length;
        i=0;

        // updating counter
        cnt.text(i+1 + ' / ' + amount);

        setTimeout(run, interval);
    };
})(jQuery);

