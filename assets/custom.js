$(function () {
    $('.addError').hide();
    $('.control').click(function () {
        var key = $(this).data('key');
        $('#focusId').val(key);
    })
    var relationList = [];

    $('#delete').click(function () {
        var id = $('#focusId').val();
        id = parseInt(id);
        $.ajax({
            type: 'POST',
            url: './deleteRelation.php',
            data: {id: id},
            dataType: 'json',
            success: function (data) {
                window.location.reload();
            }
        });
    });

    $('#addRelation').click(function () {
        var type = $('#type').val();
        var id = $('#focusId').val();
        id = parseInt(id);
        $.ajax({
            type: 'POST',
            url: './addRelation.php',
            data: {id: parseInt(id), type: type, relationList: relationList},
            dataType: 'json',
            success: function (data) {
                if (data.result == "success") {
                    window.location.reload();
                } else {
                    console.log('asdfasdf');
                    $('.addError').show();
                    $('.addError').text(data.message);
                }
            }
        });
    });
    $('.btn-search').click(function () {
        $('#resultData').empty();
        var firstName = "";
        var middleName = "";
        var lastName = "";
        var birthday = "";
        firstName = $('#firstname').val();
        middleName = $('#middlename').val();
        lastName = $('#lastname').val();
        birthday = $('#birthday').val();
        $.ajax({
            type: 'POST',
            url: './search.php',
            data: {firstName: firstName, middleName: middleName, lastName: lastName, birthday: birthday},
            dataType: 'json',
            success: function (data) {
                var template = '<tr class="text-center"><td>#</td><td>Image</td><td>First Name</td><td>Last Name</td><td>relationship</td></tr>';
                for (var i = 0; i < data.length; i++) {
                    var _template = '<tr class="text-center"><td>' + data[i]['id'] + '</td>';
                    _template += '<td><img src="' + data[i]['profileImage'] + '" class="rounded-circle" style="width: 80px"/></td>' +
                        '<td>' + data[i]['firstName'] + '</td>' +
                        '<td>' + data[i]['lastName'] + '</td>' +
                        '<td><input type="checkbox" class="checkbox" id="' + data[i]['id'] + '"/></td></tr>';
                    template += _template;
                }
                $('#resultData').append(template);
                $('input[type="checkbox"]').click(function () {
                    if ($(this).prop("checked") == true) {
                        relationList.push($(this).attr('id'));
                    } else if ($(this).prop("checked") == false) {
                        relationList.remove($(this).attr('id'));
                    }
                });
            }
        })

    })
    Array.prototype.remove = function () {
        var what, a = arguments, L = a.length, ax;
        while (L && this.length) {
            what = a[--L];
            while ((ax = this.indexOf(what)) !== -1) {
                this.splice(ax, 1);
            }
        }
        return this;
    };

})