var isMobile = $(window).width() <= 600;
var file = null;
var rightClick = {
    messageID: '',
    userID: '',
    lineID: ''
}


$(window).on('orientationchange', function () {
    isMobile = $(this).width() <= 600;
})
    .on('resize', function () {
    isMobile = $(this).width() <= 600;
});



$('.message-box').on('input', function () {
    $(this).css('height', '').css('height', Math.min($(this).prop('scrollHeight'),isMobile ? 45 : 75) + "px");
    $('.free-space').css('height', Math.min($(this).prop('scrollHeight'), isMobile ? 45 : 75) + "px");
    $('.send-button').css('display', $(this).val() === "" ? "none" : "block");
    $('.select-file').css('display', $(this).val() === "" ? "block" : "none");
});

$('document').ready(function () {
    $('.message-box').css('height', '25px');
    // $('.add-student-id').on('keypress',function () {
    //
    //         var charCode = (e.which) ? e.which : event.keyCode
    //
    //         if (String.fromCharCode(charCode).match(/[^0-9]/g))
    //             $(this).val('');
    // });
});
//    $('form').on('submit', function (e) {
//        e.preventDefault();
//        var formData = $('form').serialize();
//
//        $.ajax({
//            type : 'POST',
//            data : formData,
//            url : 'send.php',
//            dataType : 'json',
//            encode : true,
//            success : function(response) {
//                console.log(response);
//                if($("#message-textarea").val() === "") return;
//
//                var liString = '<li class="message-line their-message" dir="rtl"><div class="message">' +
//                    $("#message-textarea").val() +
//                    '</div></li>';
//
//                $("#mess-list").prepend(liString.replace(/\n/g, "<br />"));
//                $("#message-textarea").val("");
//                $("#message-textarea").focus().height("");
//                $("#free-space").height("");
//            },
//            error : function (err) {
//                console.log(err);
//            }
//        })
//    })
// });

$('form').on('submit', function (e) {
    e.preventDefault();
});

function sendMessage(i) {
    // var message = $('message-textarea' + i).val();
    // var fr = $('#chat-footer' + i);

    // $.ajax({
    //     url: "send.php",
    //     type: "POST",
    //     dataType: "json",
    //     data: fr.serialize(),
    //     success: function (dat) {
    //         // var i = fr.children('input').first().val();
    //         var message = $('#message-textarea' + i);
    //
    //         if (message === "" && file === null)
    //             alert("لطفا پیامی بنویسید.");
    //         else if (file != null) {
    //             if (!isImage(file)) {
    //                 alert('نمی توان ارسال کرد');
    //                 return;
    //             }
    //             if (dat === 'has not message') {
    //                 alert(dat);
    //                 return;
    //             }
    //
    //             var liString = '<li class="message-line their-message" dir="rtl">' +
    //                 '<div class="message" style="display: flex; flex-flow: column; padding: 1px">' +
    //                 '<img class="message-image" src="' + URL.createObjectURL(file) + '">' + message + '</div></li>';
    //
    //             $("#mess-list" + i).prepend(liString.replace(/(?:\r\n|\r|\n)/g, '<br>'));
    //             document.getElementById('message-textarea' + i).value = "";
    //             $("#message-textarea" + i).focus().height("").trigger('input');
    //
    //             $('#message-contain' + i).css('display', 'none');
    //             $('#file-dialog' + i).val('');
    //             file = null;
    //             $('#message-textarea' + i).css('border-radius', '10px').attr('placeholder', 'پیام');
    //         }
    //         else {
    //             var liString = '<li class="message-line their-message" dir="rtl"><div class="message">' +
    //                 message + '</div></li>';
    //
    //             $("#mess-list" + i).prepend(liString.replace(/(?:\r\n|\r|\n)/g, '<br>'));
    //             document.getElementById('message-textarea' + i).value = "";
    //             $("#message-textarea" + i).focus().height("").trigger('input');
    //         }
    //     },
    //     error: function (xhr, textStat, errorThrownn) {
    //         // alert(xhr.responseText);
    //     }
    // });
    //
    // return;



    // var senderId = document.getElementById('userId' + i).value;
    // var toId = document.getElementById('toId' + i).value;
    var message = $('#message-textarea' + i).val();

    if(message === "" && file === null)
        alert("لطفا پیامی بنویسید.");
    else if(file != null) {
        // if (!isImage(file)) {
        //     alert('نمی توان ارسال کرد');
        //     return;
        // }

        // $.get('send.php', {
        //     message: message.replace(/(?:\r\n|\r|\n)/g, '<br>'),
        //     userid: senderId,
        //     toid: toId,
        //     file_name: file.name,
        //     file_url: URL.createObjectURL(file)}
        // $.get('send.php', new FormData($('#chat-footer' + i))
        // ).done(function (data) {
        //     var liString = '<li class="message-line their-message" dir="rtl"><div class="message" style="display: flex; flex-flow: column; padding: 1px"><img class="message-image" src="' + URL.createObjectURL(file) + '">' +
        //         message + '</div></li>';
        //
        //     $("#mess-list" + i).prepend(liString.replace(/(?:\r\n|\r|\n)/g, '<br>'));
        //     document.getElementById('message-textarea' + i).value = "";
        //     $("#message-textarea" + i).focus().height("").trigger('input');
        //
        //     $('#message-contain' + i).css('display', 'none');
        //     $('#file-dialog' + i).val('');
        //     file = null;
        //     $('#message-textarea' + i).css('border-radius', '10px').attr('placeholder', 'پیام');
        //
        //     // alert(data);
        // });
        //
        // var dataa = new FormData(fr);
        // alert(JSON.stringify(dataa));
            var formd = new FormData();
            formd.append('message', $('#message-textarea' + i).val());
            formd.append('toid', $('#toId' + i).val());
            formd.append('userid', $('#userId' + i).val());
            formd.append('fileselector', file);
            formd.append('isgroup', $('#isGroup' + i).val());
            formd.append('replyto', $('#replyTo' + i).val());


            $.ajax({
                url: "send.php",
                data: formd,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (dat) {
                    if (dat === 'has not message') {
                        alert(dat);
                        return;
                    }
                    var liString = null;

                    if(isImage(file)) {
                        liString = '<li class="message-line their-message" dir="rtl">' +
                            '<div class="message" style="display: flex; flex-flow: column; padding: 1px">' +
                            '<img alt="image" class="message-image" src="' + URL.createObjectURL(file) + '">' + message + '</div></li>';

                    }
                    else {
                        liString = '<li class="message-line has-file their-message" dir="rtl">' +
                            '                                        <div class="message-file-container" dir="ltr">' +
                            '                                            <img alt="file" class="message-file-image" src="images/file-icon.jpg">' +
                            '                                            <p class="file-name">' + file.name + '</p>' +
                            '                                        </div>' +
                            '                                        <div class="message" style="display: flex; flex-flow: column; padding: 5px; margin-top: 0">' + message + '</div>' +
                            '                                    </li>'
                    }

                    $("#mess-list" + i).prepend(liString.replace(/(?:\r\n|\r|\n)/g, '<br>'));

                    document.getElementById('message-textarea' + i).value = "";
                    $("#message-textarea" + i).focus().height("").trigger('input');

                    $('#message-contain' + i).css('display', 'none');
                    $('#file-dialog' + i).val('');
                    file = null;
                    $('#message-textarea' + i).css('border-radius', '10px').attr('placeholder', 'پیام');
                    $('#replyLink' + i).css('display', 'none');
                },
                error: function (xml, status, str) {
                    alert(xml.responseText);
                }
            });
    }

    else {
        // const xhttp = new XMLHttpRequest();
        // xhttp.onload = function () {
        //     var liString = '<li class="message-line their-message" dir="rtl"><div class="message">' +
        //     message + '</div></li>';
        //
        //     $("#mess-list" + i).prepend(liString.replace(/(?:\r\n|\r|\n)/g, '<br>'));
        //     document.getElementById('message-textarea' + i).value = "";
        //     $("#message-textarea" + i).focus().height("");
        //     $(".free-space").height("");
        // }
        // xhttp.open("GET", "send.php?message=" + message.replace(/(?:\r\n|\r|\n)/g, '<br>') + "&userid=" + senderId + "&toid=" + toId);
        // xhttp.send();
        //
        // $.get('send.php',
        //     {
        //     message: message.replace(/(?:\r\n|\r|\n)/g, '<br>'),
        //     userid: senderId,
        //     toid: toId}
        // )
        //     .done(function (data) {
        //         var liString = '<li class="message-line their-message" dir="rtl"><div class="message">' +
        //             message + '</div></li>';
        //
        //         $("#mess-list" + i).prepend(liString.replace(/(?:\r\n|\r|\n)/g, '<br>'));
        //         document.getElementById('message-textarea' + i).value = "";
        //         $("#message-textarea" + i).focus().height("").trigger('input');
        //         // $(".free-space").height("");
        //         // alert(data);
        //     })
        //     .fail(function () {
        //         alert('پیام ارسال نشد');
        //     });
        var data = $('#chat-footer' + i).serialize();

        $.ajax({
            url: "send.php",
            data: data,
            type: 'POST',
            success: function (dat) {
                if(dat === 'has not message') {
                    alert(dat);
                    return;
                }
                var liString = '<li class="message-line their-message" dir="rtl"><div class="message">' +
                                message + '</div></li>';

                $("#mess-list" + i).prepend(liString.replace(/(?:\r\n|\r|\n)/g, '<br>'));
                document.getElementById('message-textarea' + i).value = "";
                $("#message-textarea" + i).focus().height("").trigger('input');
                $('#replyLink' + i).css('display', 'none');
            },
            error: function(xhr, textStat, errorThrownn) {
                alert(xhr.responseText);
            }
        });
    }
}

function changeDialog(inp, i){
    file = inp.files[0];

    $('#message-textarea'
+ i).attr('placeholder', 'کپشن').css('border-radius', '0').focus();
    $('#message-contain' + i).css('display', 'flex');
    $('#file-cap' + i).html(file.name);

    if(isImage(file)){
        $('#sending-file-image' + i).attr('src', URL.createObjectURL(file)).css('max-width', '75px');;
    }
    else {
        $('#sending-file-image' + i).attr('src', 'images/file-icon.jpg').css('max-width', '35px');
    }

    $('#send-btn' + i).css('display', 'block');
    $('#select-files' + i).css('display', 'none');
}

$(".menu-button span").on('click', function () {
    $(".menu-button").toggleClass('active');
})

$("#logout-btn").on('click', function () {
    $(location).attr('href', 'logout.php');
});

// $("#send-btn").on('click', function () {
//     if($("#message-textarea").val() === "") return;
//
//     var liString = '<li class="message-line their-message" dir="rtl"><div class="message">' +
//         $("#message-textarea").val() +
//         '</div></li>';
//
//     $("#mess-list").prepend(liString.(/\n/g, "<br />"));
//     $("#message-textarea").val("");
//     $("#message-textarea").focus().height("");
//     $("#free-space").height("");
// });

$("message-textarea").keydown(function (e) {
    if (e.key === 13 && e.ctrlKey) {
        if($("#message-textarea").val() === "") return;

        var liString = '<li class="message-line their-message" dir="rtl"><div class="message">' +
            $("#message-textarea").val() +
            '</div></li>';

        $("#mess-list").prepend(liString.replace(/\n/g, "<br />"));
        $("#message-textarea").val("");
        $("#message-textarea").focus();
    }
});

$(document).bind('contextmenu', function (e) {
    if($(e.target).parents('.messages-list').length > 0) {
        //Is on message line
        e.preventDefault();
        $(".context-menu").finish().toggle(100).css({
           top: e.pageY + 'px',
           left: e.pageX + 'px'
        });

        rightClick['messageID'] = null;

        var li = ($(e.target).parents('.message-line').length > 0) ? $(e.target).parent('.message-line') : $(e.target);

        if(li.attr('data-action') !== undefined)
            rightClick['messageID'] = li.css('background', '#434b6873').attr('data-action');
        else
            rightClick['messageID'] = li.css('background', '#434b6873').attr('id').replace('line', '');

        rightClick['lineID'] = li.attr('id');

        // alert('line: ' + rightClick["lineID"] + ' \n' + 'message: ' + rightClick['messageID']);
    }
});

$('.chat-body').bind('mousedown', function (e) {
    if(!$(e.target).parents('.context-menu').length > 0) {
        $('.context-menu').hide(100);
        $('.message-line').css('background', 'transparent');
    }

    rightClick["lineID"] = '';
    rightClick["messageID"] = '';
});

$('.context-menu #forward-line').on('click', function () {
        $('.context-menu').hide(100);
        $('.other-pages').css('display', 'block');
        $('#select-forward-chat').css('display', 'flex').siblings().css('display', 'none');
        $('.message-line').css('background', 'transparent');
});

$('.context-menu #reply-line').on('click', function () {
    $('.context-menu').hide(100);

    var id = rightClick['userID'];
    var messID = rightClick['lineID'].replace('line', '');
    var message = $('mess' + messID).html()

    alert(message);

    $('#reply' + id);
    $('#replyLink' + id).css('display', 'block').attr('href', '#line' + messID);
    $('.message-line').css('background', 'transparent');
    $('#replyTo' + id).val(messID);
});



function selectFileClick(i) {
    $('#file-dialog' + i).trigger('click');
}

$('.other-pages').on('click', function (e) {
   if(e.target !== this)
       return;

   $(this).css('display', 'none');
});

$('#add-chat-btn').on('click', function () {
   $('.other-pages').css('display', 'block');
   $('.menu-button').removeClass('active');
});

$(".back-div").on('click', function () {
    if(isMobile) {
        $('.chat-page').css('width', '0');
        $('.chat-page > p').css('display', 'none');
    }
    else {
        $('.chat-page > p').css('display', 'block');
    }

    $('.chat-nav').removeClass('active');
   $('.chat-page').removeClass('active');
   $('.chat-view').removeClass('active');
   rightClick['userID'] = '';


});

$('.select-forward-list .forward-item').on('click', function (e) {
    try {
        var i = $(this).attr('data-action');

        var formd = new FormData();
        formd.append('toid', i);
        formd.append('userid', $('#userId' + i).val());
        formd.append('isgroup', $('#isGroup' + i).val());
        formd.append('forwardid', rightClick['messageID']);
        formd.append('replyto', $('replyTo' + i).val());

        $.ajax({
            url: "send.php",
            data: formd,
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            success: function (dat) {
                alert('Forwarded.')

                $('.other-pages').css('display', 'none');
            },
            error: function (xml, a, b) {
                alert(xml.responseText);
            }
        });
    }
    catch (e) {
        alert(e.message);
    }
});




function chatView(i) {
    if(isMobile) {
        $('.chat-page').css({'width' : '100%', 'display' : 'block'});
    }

    $('#view' + i).addClass('active').siblings().removeClass('active');
    $('.chat-page').css('display', 'block').addClass('active');
    $('.chat-page').css('display', 'block').addClass('active');
    // $("#chatn" + i).css('display', 'flex').siblings().css('display', 'none');
    $("#chatn" + i).addClass('active').siblings().removeClass('active');
    $(".chat-page > p").css('display','none');
    rightClick['userID'] = i;
}

function isImage(file) {
    return file['type'].split('/')[0] == 'image';
}

function addChat() {
    var form = $('<form action="index.php" method="post">' +
        '<input type="hidden" name="chat_to_add" value="' + $('#add-chat-text').prop('value') + '"/></form>');
    $('body').append(form);
    form.submit();
}

function closeFile(i) {
    $('#message-contain' + i).css('display', 'none');
    $('#file-dialog' + i).val('');
    file = null;
    $('#message-textarea' + i).css('border-radius', '10px').attr('placeholder', 'پیام');
}

function deleteReply(i) {
    $('#replyLink' + i).css('display', 'none');
    rightClick['messageID'] = '';
    rightClick['lineID'] = '';
}
