/**
 * Created by Bui on 05/06/2017.
 */
jQuery(document).ready(function () {
    ADMIN.deleteItem();
    ADMIN.checkAllItem();
    ADMIN.additemActionModul();
    ADMIN.additemGia();
    ADMIN.showImgIsSelect();
    ADMIN.restoreItem();
    ADMIN.additemLink();
    ADMIN.showModalIMG();
    ADMIN.scrolltoTopAdmin();
    ADMIN.setupOptionSelectDate();
    ADMIN.changeSTT();
});
ADMIN = {
    setupOptionSelectDate: function () {
        var dateToday = new Date();
        jQuery('.date').datetimepicker({
            timepicker: false,
            format: 'd-m-Y',
            lang: 'vi',
            // minDate: dateToday,
        });
    },
    deleteItem: function () {
        jQuery(document).on('click', '#deleteMoreItem', function () {
            var total = jQuery("input:checked").length;
            if (total == 0) {
                jAlert('Vui lòng chọn ít nhất 1 bản ghi để xóa!', 'Thông báo');
                return false;
            } else {
                jConfirm('Bạn muốn xóa [OK]:Đồng ý [Cancel]:Bỏ qua?)', 'Xác nhận', function (r) {
                    if (r) {
                        jQuery('form#formListItem').submit();
                        return true;
                    }
                });
                return false;
            }
        });
    },
    checkAllItem: function () {
        jQuery(document).on('click', "input#checkAll", function () {
            var checkedStatus = this.checked;
            jQuery("input.checkItem").each(function () {
                this.checked = checkedStatus;
            });
        });
    },
    additemActionModul: function () {
        jQuery(document).on('click', '#themAction', function () {
            var rel = Number(jQuery(this).attr('rel'));
            rel = rel + 1;
            jQuery('#sys_drag_sort_action').append("<li id='sys_div_sort_other_" + rel + "' ><div class='itemAction div_sort_order'>" +
                "<input type='checkbox' checked name='module_action[]' value='' id='valueAction" + rel + "'/>" +
                "<input type='text'  id='nameAction'  class='form-control ' rel='" + rel + "'>" +
                "<i id='xoa' class='fa fa-remove fa-admin red'  rel='" + rel + "'> </i></div></li>");
            jQuery(this).attr('rel', rel);
        });
        jQuery('#sys_drag_sort_action').on('keyup', '#nameAction', function () {
            var txt = jQuery(this).val();
            var rel = jQuery(this).attr('rel');
            jQuery('#valueAction' + rel).val(txt);
        });
        jQuery('#sys_drag_sort_action').on('click', '#xoa', function () {
            var rel = Number(jQuery(this).attr('rel'));
            jQuery("ul").children().remove('#sys_div_sort_other_' + rel);
            rel = rel - 1;
            if (rel <= 0) rel = 0;
            jQuery('#themdapan').attr('rel', rel);
        });
    },
    additemLink: function () {
        jQuery(document).on('click', '#themLinkVideo', function () {
            jQuery('#listLinkVideo').append('<li><input id="product_video" type="url" class="form-control" name="product_video[]" value="" autofocus placeholder="link video intro"><i class="fa fa-remove red fa-admin" id="xoa-link"></i> </li>')
        });
        jQuery(document).on('click', '#xoa-link', function () {
            jQuery(this).parent('li').remove();
        })
    },
    additemGia: function () {
        jQuery(document).on('click', '#themGIa', function () {
            jQuery('#listGia').append('<li>Giá mua : <input class="form-control level-price" type="number" name="level[]" min="1"  autofocus value=' + (jQuery('#listGia').find('li').length + 1) + '> :' +
                '<input class="form-control price" type="number"   autofocus  name="price[]" min="0"><i class="fa fa-remove red fa-admin" id="xoa-gia"></i></li>')
        });
        jQuery(document).on('click', '#xoa-gia', function () {
            jQuery(this).parent('li').remove();
        })
    },
    showImgIsSelect: function () {
        jQuery(document).on('change', '#category_media,#product_media,#news_media,#banner_media,#gift_media,#member_avt,#cmt_avt,#partner_logo', function () {
            var tt = jQuery(this)[0].files.length > 0 ? jQuery(this)[0].files.length : '';
            jQuery('#fileName').text(tt + ' ảnh đã được chọn');
            jQuery('#privewIMG').empty();
            if (jQuery('#privewIMG').attr('rel') == 1) {
                jQuery('#remove_media').val('1');
            }
            jQuery('#privewIMG').append('<img src="' + window.URL.createObjectURL(jQuery(this)[0].files[0]) + '" width="100%"/>');
        });
        jQuery(document).on('change', '#category_media_banner', function () {
            var tt = jQuery(this)[0].files.length > 0 ? jQuery(this)[0].files.length : '';
            jQuery('#fileNameBanner').text(tt + ' ảnh đã được chọn');
            jQuery('#privewIMGBanner').empty();
            if (jQuery('#privewIMGBanner').attr('rel') == 1) {
                jQuery('#remove_media_banner').val('1');
            }
            jQuery('#privewIMGBanner').append('<img src="' + window.URL.createObjectURL(jQuery(this)[0].files[0]) + '" width="100%"/>');
        });
        jQuery(document).on('click', '#xoa-media', function () {
            jQuery('#remove_media').val('1');
            jQuery(this).parent('#privewIMG').empty();
        });
        jQuery(document).on('click', '#xoa-media-banner', function () {
            jQuery('#remove_media_banner').val('1');
            jQuery(this).parent('#privewIMGBanner').empty();
        });
        jQuery(document).on('change', '#product_multi_media', function () {
            var f = document.getElementById("product_multi_media");
            var tt = jQuery(this)[0].files.length;
            jQuery('#totalList').text(tt + ' ảnh đã được chọn');
            var listIMG = jQuery('#showListIMG');
            listIMG.find('li').each(function () {
                if (jQuery(this).attr('rel') == -1) {
                    jQuery(this).remove();
                }
            });
            if (tt > 0) {
                for (var i = 0; i < tt; i++) {
                    listIMG.append("<li class='col-md-3 col-sm-4' rel='-1'><img src='" + window.URL.createObjectURL(f.files[i]) + "' height='150px'></li>");
                }
            }
        });
        jQuery(document).on('change', '#gift_multi_media', function () {
            var f = document.getElementById("gift_multi_media");
            var tt = jQuery(this)[0].files.length;
            jQuery('#totalList').text(tt + ' ảnh đã được chọn');
            var listIMG = jQuery('#showListIMG');
            listIMG.find('li').each(function () {
                if (jQuery(this).attr('rel') == -1) {
                    jQuery(this).remove();
                }
            });
            if (tt > 0) {
                for (var i = 0; i < tt; i++) {
                    listIMG.append("<li class='col-md-3 col-sm-4' rel='-1'><img src='" + window.URL.createObjectURL(f.files[i]) + "' height='150px'></li>");
                }
            }
        });
        jQuery(document).on('click', '#xoa-multi-media', function () {
            jQuery(this).parent('li').remove();
            jQuery('.btn-option').append('<input type="hidden" id="remove_multi_media" name="remove_multi_media[]" value="' + jQuery(this).attr('rel') + '"/>');
        })
    },
    restoreItem: function () {
        jQuery(document).on('click', '#restoreMoreItem', function () {
            var total = jQuery("input:checked").length;
            if (total == 0) {
                jAlert('Vui lòng chọn ít nhất 1 bản ghi để khôi phục!', 'Thông báo');
                return false;
            } else {
                jConfirm('Bạn muốn khôi phục [OK]:Đồng ý [Cancel]:Bỏ qua?)', 'Xác nhận', function (r) {
                    if (r) {
                        jQuery('#formListItem').attr("action", BASE_URL + "admin/trash/restore");
                        jQuery('#formListItem').submit();
                        return true;
                    }
                });
                return false;
            }
        });
    },
    showModalIMG: function () {
        jQuery('#Modal-IMG').hide();
        jQuery(document).on('click', '#showIMG', function () {
            jQuery('#Modal-IMG').show();
            var src = '';
            if (jQuery(this).is('img')) {
                src = jQuery(this).attr('src');
            } else {
                src = jQuery(this).attr('rel');
            }
            jQuery('#img01').attr('src', src);
            jQuery('#modal-img-caption').text(jQuery(this).attr('title'));
        });
        jQuery('.close').on('click', function () {
            jQuery('#Modal-IMG').hide();
        });
        jQuery(document).on('click', '#Modal-IMG', function () {
            jQuery(this).hide();
        })
    },
    scrolltoTopAdmin: function () {
        jQuery(window).scroll(function () {
            var e = jQuery("body").scrollTop();
            if (e > 300) {
                jQuery(".btn-top").show();
            } else {
                jQuery(".btn-top").hide();
            }
        });
        jQuery(".btn-top").on('click', function () {
            jQuery('body').animate({
                scrollTop: 0
            })
        });
    },
    changeSTTMember: function () {
        jQuery(document).on('click', '#changeSTTMember', function () {
            var id = jQuery(this).attr('dataid');
            var stt = jQuery(this).attr('datastt');
            var cf = -1;
            var token = jQuery('meta[name="csrf-token"]').attr('content');
            var url = BASE_URL + 'admin/member/change-status.html';
            if(id>0&&stt>-1) {
                if (stt == 0) {
                    jConfirm('Phê duyệt [OK]:Đồng ý [Cancel]:Bỏ qua?)', 'Phê duyệt thành viên', function (r) {
                        if (r) {
                            cf = 1;
                        } else {
                            cf = 4;
                        }
                        sendAjax(id,cf);
                    });
                } else {
                    if (stt == 1) {
                        cf = 4;
                    }
                    if (stt == 4) {
                        cf = 1;
                    }
                    sendAjax(id,cf);
                }
            }
            function sendAjax (id,cf) {
                jQuery.ajax({
                    type: "POST",
                    url: url,
                    data: "_token=" + encodeURI(token) + "&id=" + encodeURI(id) + "&stt=" + encodeURI(cf),
                    success: function (data) {
                        if(data==1){
                            jQuery(document).find('#changeSTTMember').each(function () {
                                if(jQuery(this).attr('dataid')==id){
                                    var cls = '';
                                    if(jQuery(this).hasClass('green')) cls='green';
                                    if(jQuery(this).hasClass('red')) cls='red';
                                    if(jQuery(this).hasClass('black')) cls='black';
                                    jQuery(this).removeClass(cls);
                                    jQuery(this).attr('datastt', cf);
                                    if (cf == 4) {
                                        jQuery(this).addClass('black');
                                    }
                                    if (cf == 1) {
                                        jQuery(this).addClass('green')
                                    }
                                }
                            });
                            return;
                        }
                    }
                });
            }
        });
    },
	changeSTT:function () {
        jQuery(document).on('click', '.changestt', function () {
            var id = jQuery(this).attr('dataid');
            var stt = jQuery(this).attr('datastt');
            var type = jQuery(this).attr('datatype');
            var cf = -1;
            var token = jQuery('meta[name="csrf-token"]').attr('content');
            var url = BASE_URL + 'admin/change-status.html';
            if($(this).attr('datamulti')==1){
                if (stt == 0) {
                    jConfirm('Phê duyệt [OK]:Đồng ý [Cancel]:Bỏ qua(Ẩn)?)', 'Xác nhận phê duyệt', function (r) {
                        if (r) {
                            cf = 1;
                        } else {
                            cf = 4;
                        }
                        sendAjaxSTT(id,cf,type);
                    });
                } else {
                    if (stt == 1) {
                        cf = 4;
                    }
                    if (stt == 4) {
                        cf = 1;
                    }
                    sendAjaxSTT(id,cf,type);
                }
            }else{
                if (stt == 1) {
                    cf = 0;
                } else cf = 1;
                if (cf > -1) {
                    sendAjaxSTT(id, cf, type);
                }
            }

            function sendAjaxSTT(id, cf, type) {
                jQuery.ajax({
                    type: "POST",
                    url: url,
                    data: "_token=" + encodeURI(token) + "&id=" + encodeURI(id) + "&stt=" + encodeURI(cf) + "&type=" + encodeURI(type),
                    success: function (data) {
                        if (data == 1) {
                            jQuery(document).find('.changestt').each(function () {
                                if (jQuery(this).attr('dataid') == id) {
                                    var cls = '';
                                    if (jQuery(this).hasClass('green')) cls = 'green';
                                    if (jQuery(this).hasClass('red')) cls = 'red';
                                    if(jQuery(this).hasClass('black')) cls='black';
                                    jQuery(this).removeClass(cls);
                                    jQuery(this).attr('datastt', cf);
                                    if (cf == 1) {
                                        jQuery(this).addClass('green');
                                    }
                                    if (cf == 0) {
                                        jQuery(this).addClass('red');
                                    }
                                    if(cf==4){
                                        jQuery(this).addClass('black');
                                    }
                                    return;
                                }
                            });
                            return;
                        }
                    }
                });
            }
        });
    },
	
}
