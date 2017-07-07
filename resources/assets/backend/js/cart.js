jQuery(document).ready(function ($) {
    CART.addCart();
    CART.updatCart();
    CART.deleletAllItem();
    CART.delelteOneItem();
    CART.paymentOrder();
    CART.choserGift();
    CART.changePnum();
});

CART = {
    addCart: function () {
        jQuery('#btn-add-cart').click(function () {
            var url = BASE_URL + 'them-vao-gio-hang.html';
            var pid = jQuery(this).attr('data-id');
            var pnum = jQuery(this).attr('data-num');
            var token = jQuery('meta[name="csrf-token"]').attr('content');
            if (pid > 0 && pnum > 0) {
                jQuery('body').append('<div class="loading"></div>');
                jQuery.ajax({
                    type: "POST",
                    url: url,
                    data: "_token=" + encodeURI(token) + "&pid=" + encodeURI(pid) + "&pnum=" + encodeURI(pnum),
                    success: function (data) {
                        jQuery('body').find('div.loading').remove();
                        if (data == 1) {
                            jAlert('Đã thêm vào giỏ hàng!', 'Thông báo');
                            window.location.reload();
                        } else {
                            if (data != '') {
                                jAlert(data, 'Cảnh báo');
                            } else {
                                jAlert('Không tồn tại sản phẩm!', 'Cảnh báo');
                            }
                            return false;
                        }
                    }
                });
            }
        });
    },
    updatCart: function () {
        jQuery('#updateCart').click(function () {
            var updateCart = BASE_URL + 'gio-hang.html';
            jConfirm('Bạn có muốn cập nhật đơn hàng không [OK]:Đồng ý [Cancel]:Bỏ qua ?', 'Xác nhận', function (r) {
                if (r) {
                    jQuery('#txtFormShopCart').attr('action', updateCart).submit();
                }
            });
            return true;
        });
    },
    deleletAllItem: function () {
        jQuery(document).on('click', '#dellAllCart', function () {
            var url = BASE_URL + 'xoa-gio-hang.html';
            var token = jQuery('input[name="_token"]').val();
            var all = jQuery(this).attr('data');
            jConfirm('Bạn có muốn xóa không [OK]:Đồng ý [Cancel]:Bỏ qua ?', 'Xác nhận', function (r) {
                if (r) {
                    jQuery.ajax({
                        type: "POST",
                        url: url,
                        data: "_token=" + token + "&all=" + encodeURI(all),
                        success: function (data) {
                            if (data != '') {
                                window.location.reload();
                            }
                        }
                    });
                }
            });
            return true;
        });
    },
    delelteOneItem: function () {
        jQuery(document).on('click', '#delOneItemCart', function () {
            var url = BASE_URL + 'xoa-mot-san-pham-trong-gio-hang.html';
            var token = jQuery('input[name="_token"]').val();
            var pid = jQuery(this).attr('data');
            jConfirm('Bạn có muốn xóa không [OK]:Đồng ý [Cancel]:Bỏ qua ?', 'Xác nhận', function (r) {
                if (r) {
                    jQuery.ajax({
                        type: "POST",
                        url: url,
                        data: "_token=" + token + "&pid=" + encodeURI(pid),
                        success: function (data) {
                            if (data != '') {
                                window.location.reload();
                            }
                        }
                    });
                }
            });
            return true;
        });
    },
    paymentOrder: function () {
        jQuery('#submitPaymentOrder').click(function () {
            var valid = true;
            var rgphone = new RegExp(/^0(1\d{9}|9\d{8})$/);
            if (jQuery('#txtName').val().length<1) {
                jQuery('#txtName').addClass('error');
                jQuery('#txtName').focus();
                valid = false;
            } else {
                jQuery('#txtName').removeClass('error');
            }

            if (jQuery('#txtMobile').val().length<1|| !rgphone.test(jQuery('#txtMobile').val())) {
                jQuery('#txtMobile').addClass('error');
                jQuery('#txtMobile').focus();
                valid = false;
            } else {
                    jQuery('#txtMobile').removeClass('error');
            }
            if (jQuery('#txtAddress').val() == '') {
                jQuery('#txtAddress').addClass('error');
                jQuery('#txtAddress').focus();
                valid = false;
            } else {
                jQuery('#txtAddress').removeClass('error');
            }
            if (valid == false) {
                return false;
            }
            return valid;
        });
    },
    choserGift: function () {
        jQuery(document).on('click', '#chooserGift', function () {
			jQuery('#chooserGift.active').removeClass('active');
            jQuery(this).addClass('active');
            jQuery('#txtGift').attr('name', jQuery(this).attr('keys'));
            jQuery('#txtGift').val(jQuery(this).attr('data'));
        });
    },
    changePnum: function () {
        var url = BASE_URL + 'gia-san-pham.html';
        jQuery(window).on('load', function () {
            var pid = jQuery('#txtPid').val();
            var pnum = jQuery('#txtPnum').val();
            var token = jQuery('input[name="_token"]').val();
            if(pnum<1){
                jQuery('#txtPnum').val('1');
                pnum=1;
            }
            jQuery.ajax({
                type: "POST",
                url: url,
                data: "_token=" + token + "&pid=" + encodeURI(pid) + "&pnum=" + encodeURI(pnum),
                success: function (data) {
                    if (data != '') {
                        jQuery('#totalPrice').text(CART.formatNumber(data*pnum) + 'đ');
                        jQuery('#txtPrice').text(CART.formatNumber(data)+'đ/sản phẩm');
                    }
                }
            });
        });
        jQuery(document).on('change', '#txtPnum', function () {
            var pid = jQuery('#txtPid').val();
            var pnum = jQuery(this).val();
            var token = jQuery('input[name="_token"]').val();
            if(pnum<1){
                jQuery(this).val('1');
                pnum=1;
            }
            jQuery.ajax({
                type: "POST",
                url: url,
                data: "_token=" + token + "&pid=" + encodeURI(pid) + "&pnum=" + encodeURI(pnum),
                success: function (data) {
                    if (data != '') {
                        jQuery('#totalPrice').text(CART.formatNumber(data*pnum) + 'đ');
                        jQuery('#txtPrice').text(CART.formatNumber(data)+'đ/sản phẩm');
                    }
                }
            });
        });
        jQuery(document).on('change','#Pnum',function () {
            var totalmax = jQuery('#TotalMax').attr('data');
            var pid = jQuery(this).attr('dataId');
            var totalprice = jQuery('#TotalPrice'+pid).attr('data');
            var pnum = jQuery(this).val();
            var token = jQuery('input[name="_token"]').val();
            if(pnum<1){
                jQuery(this).val('1');
                pnum=1;
            }
            jQuery.ajax({
                type: "POST",
                url: url,
                data: "_token=" + token + "&pid=" + encodeURI(pid) + "&pnum=" + encodeURI(pnum),
                success: function (data) {
                    if (data != '') {
                        var n = data*pnum;
                        jQuery('#TotalPrice'+pid).text(CART.formatNumber(n) + 'đ');
                        jQuery('#Price'+pid).text(CART.formatNumber(data)+'đ/sản phẩm');
                        totalmax = (totalmax-totalprice)+n;
						jQuery('#TotalMax').attr('data',totalmax);
						jQuery('#TotalPrice'+pid).attr('data',n);
                        jQuery('#TotalMax').text('Tổng tiền : '+CART.formatNumber(totalmax)+'đ');
                    }
                }
            });
        });
    },
    formatNumber: function (str) {
        var parts = (str + "").split("."),
            main = parts[0],
            len = main.length,
            output = "",
            first = main.charAt(0),
            i;

        if (first === '-') {
            main = main.slice(1);
            len = main.length;
        } else {
            first = "";
        }
        i = len - 1;
        while (i >= 0) {
            output = main.charAt(i) + output;
            if ((len - i) % 3 === 0 && i > 0) {
                output = "." + output;
            }
            --i;
        }
        // put sign back
        output = first + output;
        // put decimal part back
        if (parts.length > 1) {
            output += "." + parts[1];
        }
        return output;
    },
}