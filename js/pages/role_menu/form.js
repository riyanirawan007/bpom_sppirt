var formRoleMenu = $('#form-rolemenu');
var fieldId=$('#id_role_menu');
var fieldRole = $('#id_role_user');
var fieldMenu = $('#id_menu');
var fieldStat = $('#active_stat');
$('document').ready(function() {
	if(id_role_for_edit!=0)
	{
		formRoleMenu.attr('method','post');
		formRoleMenu.attr('action',base_url+'role_menu/edit');
		loadEditData(id_role_for_edit);
	}
	else
	{
		formRoleMenu.attr('method','post');
		formRoleMenu.attr('action',base_url+'role_menu/add');
	}

    fieldRole.change(function() {
        fieldMenu.html('<option value=""></option>');
        fieldMenu.select2("val","");
        $.ajax({
            type: 'GET',
            url: base_url + 'role_menu/getUnlistedMenuByRole',
            async: false,
            data: {
                role_id: fieldRole.val()
            },
            dataType: 'json',
            success: function(data, text) {
                var option = '<option value=""></option>';
                for (var i = 0; i < data.length; i++) {
                	if(data[i].is_has_listed==0 || id_role_for_edit!=0)
                	{
                    	option += '<option value="' + data[i].id_menu + '">' + data[i].title + '</option>';	
                	}
                }
                fieldMenu.html(option);
            },
            error: function(request, status, error) {}
        })
    });
    formRoleMenu.validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            id_role_user: {
                required: true
            },
            id_menu: {
                required: true
            },
            active_stat: {
                required: true
            }
        },
        messages: {
            id_role_user: "Role user menu harus diisi",
            id_menu: "Menu harus dipilih",
            active_stat: "Status menu harus dipilih"
        },
        highlight: function(e) {
            $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
        },
        success: function(e) {
            $(e).closest('.form-group').removeClass('has-error'); //.addClass('has-info');
            $(e).remove();
        },
        errorPlacement: function(error, element) {
            if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                var controls = element.closest('div[class*="col-"]');
                if (controls.find(':checkbox,:radio').length > 1) controls.append(error);
                else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
            } else if (element.is('.select2')) {
                error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
            } else if (element.is('.chosen-select')) {
                error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
            } else error.insertAfter(element.parent());
        },
        submitHandler: function(form) {
            form.submit();
        },
        invalidHandler: function(form) {}
    });
});

function loadEditData(id)
{
    $.ajax({
        type:'GET'
        ,url:base_url+'role_menu/getRoleMenu'
        ,data:{id_role_menu:id}
        ,dataType:'json'
        ,success:function(data,text)
        {
            fieldId.val(id);
            fieldRole.select2('val',data[0].id_role_user)
            fieldRole.trigger('change');
            fieldMenu.select2('val',data[0].id_menu);
            fieldStat.select2('val',data[0].active_stat);
        }
        ,error:function(req,stat,err)
        {
            alert('Error '+err);
        }
    })
}