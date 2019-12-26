var hasValueChanged = false;

function setChangeStatus(status) {
    hasValueChanged = status;
}

function addFields() {
    if (checkData()) {
        var counter = $(".mult-condition").children().length;
        var operators = ["==", "!=", ">", ">=", "<", "<="]
        var fieldInput = $("<input>")
            .attr({ name: "field[]", id: "field" + counter, placeholder: "Contidion field" })
            .addClass("form-control col-md-4 field")
            .attr('onchange', 'setChangeStatus(true)');
        var valueInput = $("<input>")
            .attr({ name: "value[]", id: "value" + counter, placeholder: "Contidion value" })
            .addClass("form-control col-md-4 value")
            .attr('onchange', 'setChangeStatus(true)');
        var operatorsDropdown = $("<select/>")
            .attr({ name: "operator[]", id: "operator" + counter })
            .addClass("form-control col-md-2 operator")
            .attr('onchange', 'setChangeStatus(true)');
        var btnDelete = $("<button/>")
            .attr({ name: "action[]", id: "action" + counter })
            .addClass("btn btn--link-danger font-weight-normal")
            .append("<i/>").addClass("fa fa-minus-circle")
            .attr('onClick', 'removeCondition(' + counter + ')');
        var conditions = $('.mult-condition');
        var row = $('<div class="row"></div>');
        var field = $('<div class="col-md-2"></div>');
        var operator = $('<div class="col-md-1"></div>');
        var value = $('<div class="col-md-2"></div>');
        var btn = $('<div class="col-md-1"></div>');

        $.each(operators, function (index, value) {
            operatorsDropdown.append($("<option/>").val(value).html(value))
        })

        $(field).append(fieldInput);
        $(operator).append(operatorsDropdown);
        $(value).append(valueInput);
        $(btn).append(btnDelete);
        $(row).append(field, operator, value, btn);
        $(conditions).append(row);
    }
}

function checkData() {
    var flag = true;
    $(".error-field").html("");
    $(".error-value").html("");
    $(".field").each(function () {
        if ($(this).val() == "") {
            $(".error-field").html("Please enter field")
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

function removeCondition(row) {
    $(".error-field").html("");
    $(".error-value").html("");
    $("#field" + row).remove();
    $("#operator" + row).remove();
    $("#value" + row).remove();
    $("#action" + row).remove();
    setChangeStatus(true);
}

function clearOldErrorMessage() {
    $(".webhook_id").html("");
    $(".content").html("");
    $(".fields").remove();
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

function getValues(items) {
    return items.map(function () { return $(this).val(); }).get();
}

function showCopiedTitle(element) {
    element.setAttribute('data-toggle', 'tooltip');
    element.setAttribute('data-placement', 'top');
    element.setAttribute('data-original-title', 'Copied!');
    $('[data-toggle="tooltip"], .enable-tooltip').tooltip({ container: 'body', animation: false });
    $('#' + element.id).mouseover();
}

function getPrettyParams() {
    var params = $("textarea[name='params']").val();
    try {
        return JSON.stringify(JSON.parse(params), null, 2);
    }
    catch (error) {
        $(".params").html("Payload params is invalid JSON format");
        toastr.error('There are some invalid inputs. Please check the fields and fix them.', {
            timeOut: 4000
        });

        return false;
    }

}

$(document).ready(function () {
    $("#submit").click(function (e) {
        e.preventDefault();
        if (checkData()) {
            var _token = $('meta[name="csrf-token"]').attr('content');
            var content = $("textarea[name='content']").val();
            var params = $("textarea[name='params']").val();
            var webhook_id = $("input[name='webhook_id']").val();
            var fields = getValues($("input[name^='field[]']"));
            var operators = getValues($("select[name^='operator[]']"));
            var values = getValues($("input[name^='value[]']"));

            $.ajax({
                url: "/webhooks/" + webhook_id + "/payloads",
                type: "POST",
                data: {
                    _token: _token,
                    content: content,
                    params: params,
                    fields: fields,
                    operators: operators,
                    values: values,
                },
                success: function (id) {
                    window.location.replace("/webhooks/" + webhook_id + "/payloads/" + id + "/edit");
                },
                error: function (data) {
                    toastr.error(' Something went wrong', 'Create failed', { timeOut: 4000 });
                    clearOldErrorMessage();
                    printErrorMsg(data.responseJSON.errors);
                }
            });
        }
    });

    $("#update").click(function (e) {
        e.preventDefault();
        if (checkData()) {
            var _token = $('meta[name="csrf-token"]').attr("content");
            var content = $("textarea[name='content']").val();
            var params = $("textarea[name='params']").val();
            var webhook_id = $("input[name='webhook_id']").val();
            var fields = getValues($("input[name^='field[]']"));
            var operators = getValues($("select[name^='operator[]']"));
            var values = getValues($("input[name^='value[]']"));
            var ids = $("input[name^='field[]']").map(function () { return $(this).attr("data-id"); }).get();
            var conditions = [];

            for (i = 0; i < fields.length; i++) {
                conditions.push({
                    id: ids[i] ? ids[i] : "",
                    field: fields[i],
                    operator: operators[i],
                    value: values[i],
                });
            }

            $.ajax({
                url: $("input[name='url']").val(),
                type: "PUT",
                data: {
                    _token: _token,
                    content: content,
                    params: params,
                    fields: fields,
                    conditions: conditions,
                    ids: ids,
                },
                success: function (id) {
                    window.location.replace("/webhooks/" + webhook_id + "/payloads/" + id + "/edit");
                },
                error: function (data) {
                    toastr.error("Something went wrong", "Update failed", { timeOut: 4000 });
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
                var webhook_id = $("input[name='webhook_id']").val();
                window.location.pathname = '/webhooks/' + webhook_id + '/edit';
            });
    });

    $("textarea[name='content']").on('change', function () {
        setChangeStatus(true);
    });

    $("textarea[name='params']").on('change', function () {
        setChangeStatus(true);
    });

    $('#copyAsCurl').on('click', function (e) {
        e.preventDefault();
        var webhookUrl = $("#webhookUrl").val();
        var params = getPrettyParams();
        if (params && !hasValueChanged) {
            var copiedContent = "curl -d '" + params + "' -H 'Content-Type: application/json' -X POST " + webhookUrl;
            var tempElement = document.createElement('textarea');

            tempElement.value = copiedContent;
            tempElement.setAttribute('readonly', '');
            tempElement.style = { position: 'absolute', left: '-9999px' };
            document.body.appendChild(tempElement);
            tempElement.select();
            document.execCommand('copy');
            document.body.removeChild(tempElement);

            showCopiedTitle(document.getElementById('copyAsCurl'));
        } else if (hasValueChanged) {
            toastr.error('Cannot copy, Please save all data first', {
                timeOut: 4000
            });
        }
    });
});
