var tableMenu;
$('document').ready(function(){
    tableMenu=$('#table-menu').DataTable({
		bAutoWidth: false,
		"aoColumns": [null, null,null, null, null,null,null,null,null,
		{ "bSortable": false }],
		"aaSorting": [],
	});
	getMenuData();
});

function getMenuData()
{
	$.ajax({
		type:'GET'
		,url:base_url+'menu/getMenuData'
		,async:false
		,dataType:'json'
		,success:function(data,text)
		{
			tableMenu.clear();
			for(var i=0;i<data.length;i++)
			{
				var parent=data[i].parent_title;
				/*if(data[i].parent_menu_id!=0)
				{
					if(data[i].level=='Level 2')
					{
						$.ajax({
							type:'GET'
							,url:base_url+'menu/getMenuData'
							,async:false
							,dataType:'json'
							,data:{id_menu:data[i].parent_menu_id}
							,success:function(data2,text)
							{
								parent=data2[0].title;
							}
						});	
					}
					else if(data[i].level=='Level 3')
					{
						$.ajax({
							type:'GET'
							,url:base_url+'menu/getMenuData'
							,async:false
							,dataType:'json'
							,data:{id_menu:data[i].parent_menu_id}
							,success:function(data2,text)
							{
								parent=data2[0].title;
								$.ajax({
									type:'GET'
									,url:base_url+'menu/getMenuData'
									,async:false
									,dataType:'json'
									,data:{id_menu:data2[0].parent_menu_id}
									,success:function(data3,text)
									{
										parent+=' > '+data3[0].title;
									}
								});	
							}
						});		
					}
					else
					{
						parent='';
					}

				}*/

				var link='';
				if(data[i].link!='#'){
					link='<a href="'+data[i].link+'" >'+data[i].link+'</a>';	
				}
				else{
					link='<a>'+data[i].link+'</a>';
				}

				var icon ='<div align="center" style="font-size: 1.5em"><i class="icon fa '+data[i].fa_icon+'"/></div>';

				var action='\
				<td>\
				<div class="hidden-sm hidden-xs btn-group">\
				<button title="Edit" class="btn btn-xs btn-info" onclick="editMenu('+data[i].id_menu+')">\
				<i class="ace-icon fa fa-pencil bigger-120"></i>\
				</button>\
				\
				<button title="Hapus" class="btn btn-xs btn-danger" onclick="deleteMenu('+data[i].id_menu+',\''+data[i].title+'\')">\
				<i class="ace-icon fa fa-trash-o bigger-120"></i>\
				</button>\
				</div>\
				\
				<div class="hidden-md hidden-lg">\
				<div class="inline pos-rel">\
				<button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto" aria-expanded="false">\
				<i class="ace-icon fa fa-cog icon-only bigger-110"></i>\
				</button>\
				\
				<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">\
				<li>\
				<a onclick="editMenu('+data[i].id_menu+')" class="tooltip-success" data-rel="tooltip" title="" data-original-title="Edit">\
				<span class="green">\
				<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>\
				</span>\
				</a>\
				</li>\
				\
				<li>\
				<a onclick="deleteMenu('+data[i].id_menu+',\''+data[i].title+'\')" class="tooltip-error" data-rel="tooltip" title="" data-original-title="Hapus">\
				<span class="red">\
				<i class="ace-icon fa fa-trash-o bigger-120"></i>\
				</span>\
				</a>\
				</li>\
				</ul>\
				</div>\
				</div>\
				</td>\
				';

				tableMenu.row.add([
					(i+1)
					,data[i].title
					,data[i].place
					,data[i].level
					,parent
					,data[i].link_type
					,link
					,icon
					,data[i].sort_after
					,action
					]);
			}
			tableMenu.draw();

		}
		,error:function(request,status,error)
		{
		    alert(error);
			tableMenu.clear();
		}
	});
}

function deleteMenu(id,menuTitle)
{
	bootbox.confirm({
		message: "Apabila menu menjadi parent untuk menu lain, maka menu dibawahnya akan terhapus pada role menu masing-masing user, Anda yakin ingin menghapus menu "+menuTitle+" ?",
		buttons: {
			confirm: {
				label: "Ya",
				className: "btn-primary btn-sm",
			},
			cancel: {
				label: "Tidak",
				className: "btn-sm",
			}
		},
		callback: function(result) {
			if(result === true){
				$.ajax({
					type:'POST'
					,url:base_url+'menu/delete'
					,dataType:'json'
					,async:true
					,data:{id_menu:id}
					,success:function(data,text)
					{
						bootbox.dialog({
							closeButton:false,
							message: "<span class='bigger-110'>Data menu "+menuTitle+" berhasil dihapus!</span>",
							buttons: 			
							{
								"click" :
								{
									"label" : "OK",
									"className" : "btn-sm btn-primary",
									"callback": function() {
										document.location.href=base_url+'menu';
										// getMenuData();
									}
								}
							}
						});

					}
					,error:function(request,status,error)
					{
						alert('Gagal menghapus menu '+menuTitle);
					}
				});
			}
			else
			{
			}
		}
	});
}

function editMenu(id)
{
	$.redirect(base_url+'menu/edit',{id_menu:id},'POST');
}