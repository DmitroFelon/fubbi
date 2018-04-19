//require('./bootstrap');

jQuery(document).ready(function ($) {
    /*
     * Hide notifications
     * */
    $(".head-notification").show(0).delay(5000).hide(150);

    /*
     * Init subscriptions form
     * */
    $(".subscribe-btn").on('click', function () {
        var amount = $(this).attr('data-amount');
        var plan_id = $(this).attr('data-plan');

        $("#plan_id").val(plan_id);
        $(".amount").html(amount);

        $("#stripe-form-wrapper").show();
        $("html, body").animate({scrollTop: $(document).height() - $(window).height()});
    });
    var $form = $("#payment-form");
    $form.on('submit', function (e) {
        e.preventDefault();
        Stripe.setPublishableKey(stripe_pub);
        Stripe.createToken({
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
        }, function (status, response) {
            if (response.error) {
                $('.error')
                    .removeClass('hide')
                    .find('.alert')
                    .text(response.error.message);
            } else {
                // token contains id, last4, and card type
                var token = response['id'];
                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        });
    });

    /*
     * Update billing info form
     * */
    var $update_card_form = $("#update-card-form");
    $update_card_form.on('submit', function (e) {
        e.preventDefault();
        Stripe.setPublishableKey(stripe_pub);
        Stripe.createToken({
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
        }, function (status, response) {
            if (response.error) {
                $('.error')
                    .removeClass('hide')
                    .find('.alert')
                    .text(response.error.message);
            } else {
                // token contains id, last4, and card type
                var token = response['id'];
                $('#stripeToken').val(token);
                $update_card_form.unbind('submit');
                $update_card_form.submit();
            }
        });
    });

    /*
     * Init quiz form with steps
     * */
    $("#quiz-form").steps({
        bodyTag: "fieldset",
        enableAllSteps: user.role == 'client' ? false : true,
        showFinishButtonAlways: user.role == 'client' ? false : true,
        autoFocus: false,
        onInit: function (event, currentIndex) {
            if (typeof(Storage) !== "undefined") {
                if (typeof(localStorage.getItem("quiz-form-step")) !== "undefined") {
                    jQuery("#quiz-form-t-" + localStorage.getItem("quiz-form-step")).click();
                }
            }
            var switches = document.getElementsByClassName("js-switch");
            for (var i = 0; i < switches.length; i++) {
                new Switchery(switches[i], {color: '#1AB394'});
            }
        },
        onStepChanging: function (event, currentIndex, newIndex) {

            if (currentIndex > newIndex) {
                preUploadQuiz();
                return true;
            }

            var form = $("#quiz-form");

            if (currentIndex < newIndex) {
                // To remove error styles
                form.find(".body:eq(" + newIndex + ") label.error").remove();
                form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
            }

            $.validator.addMethod(
                "tagsInput",
                function (value, element) {
                    var result = ($('#themes option:selected').size() < 10);
                    return !result;
                },
                "Please fill at least 10 themes"
            );
            if (currentIndex === 0) {
                form.validate({
                    rules: {
                        "themes[]": {tagsInput: true}
                    },
                    messages: {
                        "themes[]": "Please fill at least 10 themes"
                    }
                })
            }
            form.validate().settings.ignore = ":disabled,:hidden:not('#themes')";
            if (form.valid()) {
                preUploadQuiz();
                return true;
            }
            return false;
        },
        onStepChanged: function (event, currentIndex) {
            if (typeof(Storage) !== "undefined") {
                localStorage.setItem("quiz-form-step", currentIndex);
            } else {
            }
        },
        onFinishing: function (event, currentIndex) {
            window.onbeforeunload = function () {
            }
            preUploadQuiz()
            return true;
        },
        onFinished: function (event, currentIndex) {
            var form = $(this);
            form.submit();
        }
    });

    /*
     * Init keywords form with steps
     * */
    var keyword_dropzones = [];

    $("#keywords-form").steps({
        bodyTag: "fieldset",
        enableAllSteps: true,
        showFinishButtonAlways: user.role == 'client' ? false : true,
        autoFocus: false,
        labels: {
            loading: "Loading related keywords..."
        },
        onInit: function (event, currentIndex) {
            if (typeof(Storage) !== "undefined") {
                if (typeof(localStorage.getItem("keywords-form-step")) !== "undefined") {
                    jQuery("#keywords-form-t-" + localStorage.getItem("keywords-form-step")).click();
                }
            }
        },
        onStepChanging: function (event, currentIndex) {
            preUploadKeywords();
            return true;
        },
        onStepChanged: function (event, currentIndex) {
            if (typeof(Storage) !== "undefined") {
                localStorage.setItem("keywords-form-step", currentIndex);
            } else {
            }
        },
        onFinishing: function (event, currentIndex) {
            preUploadKeywords();
            return true;
        },
        onContentLoaded: function () {
            var textareas = document.getElementsByClassName("autoheight");
            for (var i = 0; i < textareas.length; i++) {
                textareas[i].addEventListener('onload', function () {
                    var el = this;
                    setTimeout(function () {
                        el.style.cssText = 'height:auto; padding:0';
                        el.style.cssText = 'height:' + el.scrollHeight + 'px';
                    }, 0);
                }, false);
            }
            var _project_id = $("input[name=_project_id]").val();
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green'
            });
            var switches = document.getElementsByClassName("js-switch");
            for (var i = 0; i < switches.length; i++) {
                new Switchery(switches[i], {color: '#1AB394'});
            }

            keyword_dropzones.forEach(function (dropzone) {
                dropzone.off();
                dropzone.destroy();
            });
            
            var dropzone = new Dropzone('div#meta-' + idea_id + "-files", {
                url: "/ideas/" + idea_id + "/prefill_meta_files",
                paramName: 'files',
                method: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                addRemoveLinks: true,
                init: dropzone_init_meta,
                success: dropzone_success,
                removedfile: dropzone_removedfile_meta
            });

            keyword_dropzones.push(dropzone);

        },
        onFinished: function (event, currentIndex) {
            var form = $(this);
            form.submit();
        }
    });

    /*
     * Add manual keyword
     * */
    $(document).on("click", "button.keyword-input-submit", function (e) {
        e.preventDefault();
        var theme = $(this).attr('data-theme');
        var input = $('input[data-theme="' + theme + '"]').val();
        if (!input) {
            return;
        }

        var wrapper = $('div.keywords-wrapper[data-theme="' + theme + '"]');

        wrapper.append(
            '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">' +
            '<div class="i-checks">' +
            '<label><input class="keywords-checkbox" checked ' +
            'type="checkbox" name="keywords[' + theme + '][' + input + ']" ' +
            'value="true"> <i></i>' + input + '</label></div></div>'
        );

        $('input[data-theme="' + theme + '"]').val('');


        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green'
        });
    });

    /*
     * Add manual keyword question
     * */
    $(document).on("click", "button.keyword-question-input-submit", function (e) {
        e.preventDefault();
        var theme = $(this).attr('data-question-theme');
        var input = $('input[data-question-theme="' + theme + '"]').val();
        if (!input) {
            return;
        }

        var wrapper = $('div.keywords-wrapper[data-question-theme="' + theme + '"]');

        wrapper.append(
            '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">' +
            '<div class="i-checks">' +
            '<label><input class="keywords-checkbox" checked ' +
            'type="checkbox" name="keywords_questions[' + theme + '][' + input + ']" ' +
            'value="true"> <i></i>' + input + '</label></div></div>'
        );

        $('input[data-question-theme="' + theme + '"]').val('');


        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green'
        });
    });

    /*
     * Init tags inputs
     * */
    $('.tagsinput').tagsinput({
        tagClass: 'label label-primary'
    });
    var themes_order = [];
    var tag_themes_input = $('#themes');

    /*
     * Add item to from tags input to sortable list
     * */
    tag_themes_input.on('itemAdded', function (event) {
        $("#themes-order-list-wrapper").removeClass('hide');
        $('#themes-order-list').append('' +
            '<li class="list-group-item" data-value="' + event.item + '">' + event.item + '</li>'
        );
        var sorted = $("#themes-order-list").sortable("toArray", {attribute: 'data-value'});
        $("#themes_order option").remove();
        sorted.forEach(function (item) {
            $("#themes_order").append('<option selected="selected" value="' + item + '">' + item + '</option>');
        });
    });
    /*
     * Remove item from sortable list if removed from tags input
     * */
    tag_themes_input.on('itemRemoved', function (event) {
        $('.list-group-item[data-value="' + event.item + '"]').remove();
        $("#themes_order option[value='" + event.item + "']").remove();
        var sorted = $("#themes-order-list").sortable("toArray", {attribute: 'data-value'});
        $("#themes_order").val(sorted.join());
        if (tag_themes_input.val() == '') {
            $("#themes-order-list-wrapper").addClass('hide');
        }
    });
    /*
     * Init sortable
     * */
    $("#themes-order-list").sortable({
        axis: "y",
        cursor: "move",
        placeholder: "sortable-placeholder",
        forcePlaceholderSize: true,
        opacity: 0.8,
        stop: function (event, ui) {
            var sorted = $("#themes-order-list").sortable("toArray", {attribute: 'data-value'});
            $("#themes_order option").remove();
            sorted.forEach(function (item) {
                $("#themes_order").append('<option selected="selected" value="' + item + '">' + item + '</option>');
            });
        }
    });
    function showToastError(message, title = '') {

        toastr.options = {
            "closeButton": true,
            "debug": false,
            "progressBar": true,
            "preventDuplicates": false,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "400",
            "hideDuration": "1000",
            "timeOut": "7000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        toastr.error(title, message);
    }

    function validateKeywords(event, currentIndex) {
        var section = $("#keywords-form-p-" + currentIndex);
        var theme = section.attr('data-theme');
        var total_keywords = jQuery('[name^="keywords[' + theme + ']"]').length;
        var checked_keywords = jQuery('[name^="keywords[' + theme + ']"]:checked').length;

        if (total_keywords >= 5 && checked_keywords < 5) {
            showToastError('Form filling error', 'Please, chose at least 5 keywords');
            return false;
        }

        return true;
    }

    function preUploadKeywords() {
        var formData = new FormData(document.getElementById("keywords-form"));
        var _project_id = $("input[name=_project_id]").val();
        $.post({
            url: '/projects/' + _project_id + '/prefill',
            processData: false,
            contentType: false,
            data: formData
        })
    }

    function validateQuizStep() {

    }

    function preUploadQuiz() {
        var formData = new FormData(document.getElementById("quiz-form"));
        var _project_id = $("input[name=_project_id]").val();

        $.post({
            url: '/projects/' + _project_id + '/prefill',
            processData: false,
            contentType: false,
            data: formData
        });
    }

    /*
     * Init footable table
     * */
    $('.footable').footable({
        "paging": {
            "enabled": true
        }
    });
    /*
     * iChecks
     * */
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green'
    });
    /*
     * Notifications
     * */
    Echo.private('App.User.' + user.id)
        .notification(function (notification) {
            if (notification.type == 'App\\Notifications\\Chat\\Message') {
                if (conversation_id != notification.conversation_id) {
                    $('#topnav-messages-list').prepend(notification.navbar_message);
                    var messages_count_wrapper = $("#message-notifications-count");
                    var count = parseInt(messages_count_wrapper.html());
                    if (!count || isNaN(count)) {
                        count = 0;
                    }
                    count += 1;

                    messages_count_wrapper.html(count.toString())
                    toastr.options = {
                        "closeButton": true,
                        "debug": false,
                        "progressBar": true,
                        "preventDuplicates": false,
                        "positionClass": "toast-top-right",

                        "showDuration": "400",
                        "hideDuration": "1000",
                        "timeOut": "7000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }

                    var toastTitle = 'New message from: ' + notification.sender_name;

                    toastr.info(notification.message_text.substring(0, 25) + '...', toastTitle);

                } else {
                    $.get("{{url('messages/read/')}}/" + notification.conversation_id);

                }
            } else {
                if (notification.hasOwnProperty('navbar_message')) {

                    $('#topnav-alerts-list').prepend(notification.navbar_message);
                    var alerts_count_wrapper = $("#alerts-notifications-count");
                    var count = parseInt(alerts_count_wrapper.html());

                    if (!count || isNaN(count)) {
                        count = 0;
                    }

                    count += 1;
                    alerts_count_wrapper.html(count.toString());
                }
            }
        });
    /*
     * Help videos
     *
     * Show if vidie exist
     * */
    if (help_video_src) {
        $('#help-video-wrapper').show();
    }
    /*
     * Open video modal
     * */
    $('.question-btn').click(function () {
        var btn = $(this);
        swal(btn.attr('data-name'), {
            className: 'help-video-alert',
            buttons: {
                cancel: false,
                confirm: {
                    text: 'Got it'
                }
            },
            content: {
                element: "iframe",
                attributes: {
                    className: 'help-video',
                    src: btn.attr('data-player'),
                    frameborder: "0",
                    allowfullscreen: "allowfullscreen"
                }
            }
        }).then(function () {
            $('.help-video').each(function () {
                /*
                 * Stop video on modal close
                 * */
                $(this).attr('src', $(this).attr('src'));
                return false;
            });
        });
    });
    /*
     * Quiz dropzones init
     *
     * */
    var _project_id = $("input[name=_project_id]").val();
    $("#compliance_guideline-group").dropzone({
        url: "/projects/" + _project_id + "/prefill_files",
        paramName: 'compliance_guideline',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        addRemoveLinks: true,
        init: dropzone_init,
        success: dropzone_success,
        removedfile: dropzone_removedfile
    });
    $("#logo-group").dropzone({
        url: "/projects/" + _project_id + "/prefill_files",
        paramName: 'logo',
        method: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        addRemoveLinks: true,
        init: dropzone_init,
        success: dropzone_success,
        removedfile: dropzone_removedfile
    });
    $("#article_images-group").dropzone({
        url: "/projects/" + _project_id + "/prefill_files",
        paramName: 'article_images',
        method: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        addRemoveLinks: true,
        init: dropzone_init,
        success: dropzone_success,
        removedfile: dropzone_removedfile
    });
    $("#ready_content-group").dropzone({
        url: "/projects/" + _project_id + "/prefill_files",
        paramName: 'ready_content',
        method: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        addRemoveLinks: true,
        init: dropzone_init,
        success: dropzone_success,
        removedfile: dropzone_removedfile
    });
    function dropzone_init() {
        var thisDropzone = this;
        $.get('/projects/' + _project_id + '/get_stored_files',
            {collection: thisDropzone.options.paramName},
            function (data) {
                data.forEach(function (item) {
                    thisDropzone.emit("addedfile", item);
                    setDropzoneThumbnail(item, thisDropzone);
                    thisDropzone.emit("complete", item);
                });
            });
    }

    function dropzone_removedfile(item) {
        $.get('/projects/' + item.model_id + '/remove_stored_file/' + item.id);
        item.previewElement.remove();
    }

    function dropzone_success(item, response) {
        var thisDropzone = this;
        var response_data = response[0];
        item.id = response_data.id;
        item.url = response_data.url;
        item.model_id = response_data.model_id;
        item.mime_type = response_data.mime_type;
        setDropzoneThumbnail(item, thisDropzone);
    }

    function setDropzoneThumbnail(item, thisDropzone) {
        switch (item.mime_type) {
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                thisDropzone.options.thumbnail.call(
                    thisDropzone, item, '/img/docx.png'
                );
                break;
            case 'application/pdf':
                thisDropzone.options.thumbnail.call(
                    thisDropzone, item, '/img/pdf.png'
                );
                break;
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/png':
            case 'image/gif':
                thisDropzone.options.thumbnail.call(thisDropzone, item, item.url);
                break;
            default:
                thisDropzone.options.thumbnail.call(
                    thisDropzone, item, '/img/file.png'
                );
                break;
        }
    }

    function dropzone_init_meta() {
        var thisDropzone = this;
        $.get('/ideas/' + idea_id + '/get_stored_idea_files',
            {collection: thisDropzone.options.paramName},
            function (data) {
                data.forEach(function (item) {
                    thisDropzone.emit("addedfile", item);
                    setDropzoneThumbnail(item, thisDropzone);
                    thisDropzone.emit("complete", item);
                });
            });
    }

    function dropzone_removedfile_meta(item) {
        $.get('/ideas/' + item.model_id + '/remove_stored_file/' + item.id);
        item.previewElement.remove();
    }
})



