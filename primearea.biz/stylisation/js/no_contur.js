/***** Убираем жёлтый цвет input *****/
if(navigator.userAgent.toLowerCase().indexOf("chrome") >= 0 || navigator.userAgent.toLowerCase().indexOf("safari") >= 0){
    window.setInterval(function(){
        $('input:-webkit-autofill').each(function(){
            var clone = $(this).clone(true, true);
            $(this).after(clone).remove();
        });
    }, 20);
}