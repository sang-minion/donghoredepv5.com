/**
 * Created by Bui on 13/06/2017.
 */
jQuery(document).ready(function () {
    INDEX.slide_IMG_VideoIntro_Product();
    INDEX.commentProduct();
    INDEX.voteProduct();
});
INDEX = {
    slide_IMG_VideoIntro_Product: function () {
        $('.regular').slick({
            dots: true,
            infinite: true,
            slidesToShow: 6,
            slidesToScroll: 3,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        arrows: true,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 4
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        arrows: true,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        arrows: true,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 1
                    }
                }
            ]
        });
        $(document).on('click', '.show-img-product .slick-slide', function () {
            $('.slick-slide').removeClass('slick-current');
            $(this).addClass('slick-current');
            var srcimg = $(this).find('img').attr('src');
            $('.imgbig').find('img').attr('src', srcimg);
        });
        $(document).on('click', '.show-videoIntro-product .regular .embed-responsive-item', function () {
            var src = jQuery(this).attr('rel');
            $('.embed-responsive').html('<iframe src="' + src + '?autoplay=1" frameborder="0"  allowfullscreen></iframe>');
        });
        $('.embed-responsive').click(function () {
            var url = $(this).attr('dataone');
            $('.embed-responsive').html('<iframe src="' + url + '?autoplay=1" frameborder="0" allowfullscreen></iframe>');
        });
    },
    commentProduct: function () {
        var url = BASE_URL + 'post-comment.html';
        var token = jQuery('meta[name="csrf-token"]').attr('content');
        var rgphone = new RegExp(/^0(1\d{9}|9\d{8})$/);

        $(document).on('click', '.postcomment', function () {
            var cmt = '';
            var name = '';
            var phone = '';
            var check = 0;
            var role = 0;
            var cmrid = 0;
            var pid = $('#pidprd').val();
            if (pid > 0) {
                if ($('#cmt' + $(this).attr('dataip')).val().length > 0) {
                    cmt = $('#cmt' + $(this).attr('dataip')).val();
                    name = $('#txtname' + $(this).attr('dataip')).val();
                    phone = $('#txtphone' + $(this).attr('dataip')).val();
                    check = $('#check').val();
                    role = $('#roleck').val();
                    cmrid = $('#cmt' + $(this).attr('dataip')).attr('dataidrep');
                    if (check == 0) {
                        if (name.length < 1) {
                            $('#txtname' + $(this).attr('dataip')).addClass('error');
                            $('#txtname' + $(this).attr('dataip')).focus();
                        } else if (phone.length < 1 || !rgphone.test(phone)) {
                            $('#txtphone' + $(this).attr('dataip')).addClass('error');
                            $('#txtphone' + $(this).attr('dataip')).focus();
                        } else {
                            senCMT(pid, cmt, cmrid, name, phone, check, role);
                        }
                    } else {
                        senCMT(pid, cmt, cmrid, '', '', check, role);
                    }
                } else {
                    $('#cmt' + $(this).attr('dataip')).focus();
                    $('#cmt' + $(this).attr('dataip')).addClass('error');
                }
            }
        });


        // $(document).on('click','.postcomment',function () {
        //     if($('#'+$(this).attr('datacmt')).val().length>0){
        //         $('#message').html($('#'+$(this).attr('datacmt')).val());
        //         if($('#check').val()==0){
        //             $('#message').val($('#'+$(this).attr('datacmt')).val());
        //             $('#modelcomment').modal('show');
        //         }else{
        //             senCMT($('#pidprd').val(),$('#'+$(this).attr('datacmt')).val(),$('#cmtid').val(),'','',$('#check').val(),$('#roleck').val());
        //             $('#message').val('');
        //             $('#'+$(this).attr('datacmt')).val('');
        //             $('#cmtid').val(0);
        //         }
        //     }else{
        //         $('#'+$(this).attr('datacmt')).focus();
        //         $('#'+$(this).attr('datacmt')).addClass('error');
        //     }
        //
        // });
        // $(document).on('click','.sendcomment',function () {
        //    	if($('#name').val().length<=0){
        //         $('#name').focus();
        //         $('#name').addClass('error');
        // }else if($('#phone').val().length<9||!rgphone.test($('#phone').val())){
        //    		$('#phone').focus();
        //         $('#phone').addClass('error');
        // }else if($('#message').val().length<=0){
        // 	$('#message').focus();
        //         $('#message').addClass('error');
        // }else{
        //         senCMT($('#pidprd').val(),$('#message').val(),$('#cmtid').val(),$('#name').val(),$('#phone').val(),$('#check').val(),$('#roleck').val());
        //         $('#message').val('');
        //         $('#'+$(this).attr('datacmt')).val('');
        //         $('#cmtid').val(0);
        //         $('#modelcomment').modal('hide');
        // }
        // });
        function senCMT(pid, cmt, idrep, name, phone, uid, role) {
            jQuery.ajax({
                type: "POST",
                url: url,
                data: "_token=" + encodeURI(token) + "&pid=" + encodeURI(pid) + "&idrep=" + encodeURI(idrep) + "&name=" + encodeURI(name) + "&phone=" + encodeURI(phone) + "&cmt=" + encodeURI(cmt) + '&check=' + encodeURI(uid) + '&role=' + role,
                success: function (data) {
                    jAlert('Bình luận của bạn đã được ghi nhận chúng tôi sẽ có phản hồi sớm cho bạn !');
                    location.reload();
                }
            });
        }

        $(document).on('click', '.reply-comment', function () {
            $('#cmtid').val($(this).attr('dataid'));
            $('#' + $(this).attr('datapanel')).removeClass('hiddenrep').addClass('showrep');
            $('#' + $(this).attr('datapanel')).find('textarea').focus();
        });
    },
    voteProduct: function () {
        var url = BASE_URL + 'post-vote.html';
        var token = jQuery('meta[name="csrf-token"]').attr('content');
        var pid = $('#pidprd').val();
        var nst = 0;
        var check = $('#check').val();
        senVote(pid, 0, check);
        $(document).on('click', '.col-comment .vote-change .item-vote', function () {
            var active = $('#active-vote').val();
            nst = $(this).attr('rel');
            if (active == 0) {
                jConfirm('Bạn đánh giá sản phẩm này ' + nst + ' sao </br> [OK]:Đồng ý  [Cancel]:Bỏ qua ? ', 'Xác nhận', function (r) {
                    if (r) {
                        senVote(pid, nst, check);
                        active = 1;
                    } else {
                        return;
                    }
                });

            }
        });
        $(document).on('mouseenter', '.col-comment .vote-change .item-vote', function () {
            var active = $('#active-vote').val();
            if (active == 0) {
                nst = $(this).attr('rel');
                $('.vote .active').removeClass('active');
                $('.vote').find('.item-vote').each(function () {
                    if ($(this).attr('rel') <= nst) {
                        $(this).addClass('active');
                    }
                });
            }
        }).on('mouseleave', '.col-comment .vote-change .item-vote', function () {
            var active = $('#active-vote').val();
            if (active == 0) {
                $('.vote .active').removeClass('active');
            }
        });
        function senVote(pid, vnum, check) {
            jQuery.ajax({
                type: "POST",
                url: url,
                data: "_token=" + encodeURI(token) + "&pid=" + encodeURI(pid) + "&vnum=" + encodeURI(vnum) + "&check=" + encodeURI(check),
                success: function (data) {
                    var ts = JSON.parse(data);
                    $('.total-vote').text(ts['total']);
                    $('#active-vote').val(ts['ckv']);

                    $('.col-comment .vote-change .vote .active').removeClass('active');
                    $('.col-comment .vote-change .vote').find('.item-vote').each(function () {
                        if ($(this).attr('rel') <= ts['nstar']) {
                            $(this).addClass('active');
                        }
                    });

                    $('#1star .show-percent').css('width', '' + Math.round((85/100)*ts['n1']['precent']) + '%');
                    $('#1star .show-percent').text(ts['n1']['total'] + '/' + ts['total']);
                    $('#1star .count-num-start').text(ts['n1']['precent'] + '%');

                    $('#2star .show-percent').css('width', '' + Math.round((85/100)*ts['n2']['precent']) + '%');
                    $('#2star .show-percent').text(ts['n2']['total'] + '/' + ts['total']);
                    $('#2star .count-num-start').text(ts['n2']['precent'] + '%');

                    $('#3star .show-percent').css('width', '' + Math.round((85/100)*ts['n3']['precent']) + '%');
                    $('#3star .show-percent').text(ts['n3']['total'] + '/' + ts['total']);
                    $('#3star .count-num-start').text(ts['n3']['precent'] + '%');

                    $('#4star .show-percent').css('width', '' + Math.round((85/100)*ts['n4']['precent']) + '%');
                    $('#4star .show-percent').text(ts['n4']['total'] + '/' + ts['total']);
                    $('#4star .count-num-start').text(ts['n4']['precent'] + '%');

                    $('#5star .show-percent').css('width', '' + Math.round((85/100)*ts['n5']['precent']) + '%');
                    $('#5star .show-percent').text(ts['n5']['total'] + '/' + ts['total']);
                    $('#5star .count-num-start').text(ts['n5']['precent'] + '%');
                    return;
                }
            });
        }

        $(document).find('.rows-comment-s').each(function () {
            if ($(this).attr('datauid') != undefined || $(this).attr('dataip') != undefined) {
                var dtuid = $(this).attr('datauid') != undefined ? $(this).attr('datauid') : -1;
                var dtip = $(this).attr('dataip') != undefined ? $(this).attr('dataip') : '';
                var idcmt = $(this).attr('id');
                jQuery.ajax({
                    type: "POST",
                    url: url,
                    data: "_token=" + encodeURI(token) + "&pid=" + encodeURI(pid) + "&vnum=0&check=" + encodeURI(dtuid) + "&ip=" + dtip,
                    success: function (data) {
                        var ts = JSON.parse(data);
                        var t = ts['nstar'];
                        $('#'+idcmt).find('.item-vote').removeClass('active');
                        $('#'+idcmt).find('.item-vote').each(function (index) {
                            if(index<t){
                                $(this).addClass('active');
                            }
                        });
                    }
                });
            }
        });

    },
}