var formMenu=$('#form-menu');
var parent_menu_id=0;

$('document').ready(function(){
	$('#container-parent-menu').hide();

	$('#place').val('Administrator Page');

	formMenu.validate({
		errorElement: 'div',
		errorClass: 'help-block',
		focusInvalid: false,
		ignore: "",
		rules: {
			title: {
				required: true
			},
			place:{
				required:true
			},
			level:{
				required:true
			},
			link_type:{
				required:true
			}
		},

		messages: {
			title:"Judul menu harus diisi"
			,place:"Halaman menu harus dipilih"
			,level:"Level menu harus dipilih"
			,link_type:"Tipe link menu harus dipilih"
		},


		highlight: function (e) {
			$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
		},

		success: function (e) {
						$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
						$(e).remove();
					},

					errorPlacement: function (error, element) {
						if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
							var controls = element.closest('div[class*="col-"]');
							if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
							else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
						}
						else if(element.is('.select2')) {
							error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
						}
						else if(element.is('.chosen-select')) {
							error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
						}
						else error.insertAfter(element.parent());
					},

					submitHandler: function (form) {
						form.submit();
					},
					invalidHandler: function (form) {
					}
				});

	if(id_menu_for_edit!=0)
	{
		formMenu.attr('method','post');
		formMenu.attr('action',base_url+'menu/edit');
		loadEditData(id_menu_for_edit);
	}
	else
	{
		formMenu.attr('method','post');
		formMenu.attr('action',base_url+'menu/add');
		// $('#container-sort-index').hide();
	}

	// $('#parent_menu_id').change(function(){
	// 	var parent=$(this).val();
	// 	$.ajax({
	// 		type:'GET'
	// 		,url:base_url+'menu/getSortIndex'
	// 		,async:false
	// 		,dataType:'json'
	// 		,data:{parent_id:parent}
	// 		,success:function(data,text)
	// 		{
	// 			var option='<option value="0"> First </option>';
	// 			for(var i=0;i<data.length;i++)
	// 			{
	// 				option+='<option value="'+data[i].id_menu+'">'+data[i].title+'</option>';
	// 			}

	// 			$(this).html(option);
	// 		}
	// 		,error:function(request,status,error)
	// 		{
	// 		}
	// 	});
	// });
});

function levelChange(param)
{

	switch (param)
	{
		case 'Level 2':
		{
			lvl='Level 1';
			break;
		}
		case 'Level 3':
		{
			lvl='Level 2';
			break;
		}
	}

	if(param == 'Level 1')
	{
		option='<option value="0" selected >This is Parent</option>';
		$('#parent_menu_id').html(option);
		$('#parent_menu_id').select2('val','');
		$('#container-parent-menu').hide();
	}
	else
	{
		$.ajax({
			type:'GET'
			,url:base_url+'menu/getParentByLevel'
			,dataType:'json'
			,async:true
			,data:{level:lvl}
			,success:function(response)
			{
				option='';
				for(var i=0;i<response.length;i++)
				{
					var parent='';
					if(response[i].parent_menu_id!=0)
					{
						$.ajax({
							type:'GET'
							,url:base_url+'menu/getMenuData'
							,dataType:'json'
							,async:false
							,data:{id_menu:response[i].parent_menu_id}
							,success:function(response2)
							{
								parent=response2[0].title+' > ';
							}
						});	
					}
					option+='<option value="'+response[i].id_menu+'">'+parent+response[i].title+'</option>';
				}
				$('#parent_menu_id').html(option);
				if(id_menu_for_edit!=0)
				{
					$('#parent_menu_id').select2('val',parent_menu_id);
				}
				else
				{
					$('#parent_menu_id').select2('val',response[0].id_menu);
				}
				$('#container-parent-menu').show();

			}
			,error:function()
			{
				alert('Gagal memuat parent menu per level');
			}
		});	
	}

}

function prevIcon(val)
{
	$('#prev_icon').attr('class','fa '+val);
}

function loadEditData(id)
{
	$.ajax({
		type:'GET'
		,url:base_url+'menu/getMenuData'
		,async:true
		,dataType:'json'
		,data:{id_menu:id}
		,success:function(data,text)
		{
			$('#id_menu').val(data[0].id_menu);
			$('#title').val(data[0].title);
			$('#place').select2('val',data[0].place);
			$('#level').select2('val',data[0].level).trigger('onchange');
			$('#link_type').select2('val',data[0].link_type);
			parent_menu_id=data[0].parent_menu_id;
			$('#link').val(data[0].link);
			$('#fa_icon').val(data[0].fa_icon);
			prevIcon(''+data[0].fa_icon+'');
		}
	});
}