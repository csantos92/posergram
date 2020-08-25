var url = 'http://localhost/socialnetwork/public/';

window.addEventListener('load', function(){

    $('.btn-like').css('cursor', 'pointer');
    $('.btn-dislike').css('cursor', 'pointer');

    function dislike(){
        //Like button
        $('.btn-like').unbind('click').click(function(){
            $(this).addClass('btn-dislike').removeClass('btn-like');
            $(this).attr('src', url+'img/heart_1.png');

            $.ajax({
                url: url+'dislike/'+$(this).data('id'),
                type: 'GET',
                success: function(response){
                }
            });

            like();
        });
    }

    function like(){
        $('.btn-dislike').unbind('click').click(function(){
            $(this).addClass('btn-like').removeClass('btn-dislike');
            $(this).attr('src', url+'img/heart_2.png');

            $.ajax({
                url: url+'like/'+$(this).data('id'),
                type: 'GET',
                success: function(response){
                }
            });

            dislike();
        });
    }

    like();
    dislike();

    //Buscador
    $('#search').submit(function(){
        $(this).attr('action', url + 'users/' + $('#search #search-box').val());
    });
});