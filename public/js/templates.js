var hasValueChanged = false;

function setChangeStatus(status) {
    hasValueChanged = status;
}
function adminChangeStatus(status, templateId)
{
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'PUT',
            url: `/admin/templates/${templateId}/change_status`,
            data: {
                id: templateId,
                status: status
            },
            success: function(data) {
                toastr.success(data, 'Update Successfully', {timeOut: 3000, showEasing: 'linear'});
                setTimeout(function() {
                    window.location.reload();
                }, 3000);
            },
            error: function(error) {
                error = error.responseJSON;
                setTimeout(function() {
                    window.location.reload();
                }, 3000);
                toastr.error(error['message'], error['status'], {timeOut: 3000});
            }
        })
}

function checkData() {
    var flag = true;
    $(".error-field").html("");
    $(".error-value").html("");
    $(".field").each(function () {
        if ($(this).val() == "") {
            $(".error-field." + $(this).attr('id')).html("Please enter field")
            flag = false;
        }
    });
    $(".value").each(function () {
        if ($(this).val().length > 100) {
            $(".error-value").html("Value is too long (maximum is 100 characters)")
            flag = false;
        }
        if ($(this).val() == "") {
            $(".error-value").html("Please enter value")
            flag = false;
        }
    });

    return flag;
}

function clearOldErrorMessage() {
    $(".name").html("");
    $(".params").html("");
    $(".content").html("");
}

function printErrorMsg(errors) {
    $.each(errors, function (index, value) {
        if (index === 'fields') {
            $.each(value[0], function (key, message) {
                $('#' + key)
                    .after('<div class="has-error fields"><span class="help-block">' + message + '</span></div>')
            })
        } else {
            $("." + index).html(value);
        }
    })
}

$(document).ready(function () {
    $("#submit").click(function (e) {
        e.preventDefault();
        if (checkData()) {
            var _token = $('meta[name="csrf-token"]').attr('content');
            var content = $("textarea[name='content']").val();
            var params = $("textarea[name='params']").val();
            var name = $("input[name='name']").val();
            var status = $("select[name='status']").val();

            $.ajax({
                url: "/templates",
                type: "POST",
                data: {
                    _token: _token,
                    name: name,
                    content: content,
                    params: params,
                    status: status,
                },
                success: function (id) {
                    window.location.replace("/templates/" + id + "/edit");
                },
                error: function (data) {
                    toastr.error(' Something went wrong', 'Create failed', { timeOut: 4000 });
                    clearOldErrorMessage();
                    printErrorMsg(data.responseJSON.errors);
                }
            });
        }
    });

    $("#submitUpdate").click(function (e) {
        e.preventDefault();
        if (checkData()) {
            var _token = $('meta[name="csrf-token"]').attr('content');
            var url = $("input[name='url']").val();
            var content = $("textarea[name='content']").val();
            var params = $("textarea[name='params']").val();
            var name = $("input[name='name']").val();
            var template_id = $("input[name='id']").val();
            var status = $("select[name='status']").val();
            $.ajax({
                url: url,
                method: 'PUT',
                data: {
                    _token: _token,
                    name: name,
                    content: content,
                    params: params,
                    status: status,
                    id: template_id,
                },
                success: function (id) {
                    window.location.replace("/templates/" + id + "/edit");
                },
                error: function (data) {
                    toastr.error(' Something went wrong', 'Update failed', { timeOut: 4000 });
                    clearOldErrorMessage();
                    printErrorMsg(data.responseJSON.errors);
                }
            });
        }
    });

    $('.cancel-btn').on('click', function (e) {
        e.preventDefault();
        $('#cancel-confirm').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#cancel-btn', function () {
                window.location.pathname = '/templates';
            });
    });

    $("textarea[name='content']").on('change', function () {
        setChangeStatus(true);
    });

    $("textarea[name='params']").on('change', function () {
        setChangeStatus(true);
    });

    $('table[data-form="deleteForm"]').on('click', '.form-delete', function (e) {
        e.preventDefault();
        var $form = $(this);
        $('#confirm').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#delete-btn', function () {
                $form.submit();
            });
    });
    $('body').on('click', '.btn-public-wh', function() {
        var template_id = $(this).data('id');
        var template_name = $(this).data('name');
        $('#publicModal .template-name').text(template_name);
        $('#publicModal input').val(template_id);
        $('#publicModal').modal('show');
    });

    $('body').on('click', '.btn-unpublic-wh', function() {
        var template_id = $(this).data('id');
        var template_name = $(this).data('name');
        $('#unpublicModal .webhook-name').text(template_name);
        $('#unpublicModal input').val(template_id);
        $('#unpublicModal').modal('show');
    });

    $('body').on('click', '.btn-confirm-public', function() {
        updateTemplateStatus('#publicModal', 'public', 'success');
    });

    $('body').on('click', '.btn-confirm-unpublic', function() {
        updateTemplateStatus('#unpublicModal', 'unpublic', 'warning');
    });

    function updateTemplateStatus(modal_id, status, current_btn_class) {
        let template_id = $(modal_id + ' input').val();
        let item = $('.item-' + template_id);
        let status_change = (status == 'public') ? 'public' : 'unpublic';
        let opposite_status = (status == 'public') ? 'unpublic' : 'public';
        let opposite_btn_class = (current_btn_class == 'success') ? 'warning' : 'success';

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'PUT',
            url: `/templates/${template_id}/change_status`,
            data: {
                id: template_id,
                status: status_change.toUpperCase()
            },
            success: function(data) {
                $(modal_id).modal('toggle');
                var button = item.find('button.btn-public-unpublic');
                $(button).css('text-transform', 'capitalize');
                $(button).text(opposite_status);
                $(button).removeClass(`btn-${current_btn_class}`);
                $(button).removeClass(`btn-${status}-wh`);
                $(button).addClass(`btn-${opposite_btn_class}`);
                $(button).addClass(`btn-${opposite_status}-wh`);

                var template_status= $(item).find('div.template-status');
                $(template_status).removeClass(`label-${opposite_btn_class}`);
                $(template_status).addClass(`label-${current_btn_class}`);
                $(template_status).text(status_change).css('text-transform', 'capitalize');
                toastr.success(data, 'Update Successfully', {timeOut: 4000, showEasing: 'linear'});
            },
            error: function(error) {
                error = error.responseJSON;
                toastr.error(error['message'], error['status'], {timeOut: 4000});
            }
        })
    }
});
