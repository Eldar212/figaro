$(document).ready(function () {

    $('.get-code').click(function () {

        let phone = $('.phone-mask').val(),
            phone_numbers = phone.replace(/\ |\+|\(|\)/g, '');

        $.ajax({
            type: "POST",
            url: "/local/ajax/sendSmsCode.php",
            data: "number=" + phone_numbers,
            success: function (response) {
                console.log(response);
                if (response.result_description === 'success') {
                    let $entycode = $('#entrycode-layout');

                    $('.auth > div').hide();

                    $entycode.find('.bx-title').text(phone);
                    $entycode.show();
                }
            }
        });

    });

    $('.auth-by-phone-code').click(function () {

        let code = $('.phone-auth-code').val();

        $.ajax({
            type: "POST",
            url: "/local/ajax/Auth.php",
            data: "code=" + code,
            success: function (response) {
                if (response.result_description === 'success') {
                    window.location.href = '/';
                }
            }
        });

    });
});