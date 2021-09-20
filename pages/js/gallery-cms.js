$(function(){

    //$('.image-link').magnificPopup({type:'image'});

    var itemArray = [];

    $('.image-link:not(#productPopupLink)').each(function(){
        itemArray.push( {src: $(this).attr('href')} );
    });

    $('.image-link:not(#productPopupLink):first').click();

    try {

        $('#galleryPopupParent').magnificPopup({
            items: itemArray,
            type: 'image',
            gallery: {
                enabled: true
            }
        });

    }
    catch(err) {



    }



})