var tableRoleMenu;
$('document').ready(function() {
    tableRoleMenu = $('#table-rolemenu').DataTable({
        bAutoWidth: false,
        "aoColumns": [null, null, null,  {
            "bSortable": false
        }, {
            "bSortable": false
        }],
        "aaSorting": [],
    });
    getRoleMenuData();
});

function getRoleMenuData() {
    $.ajax({
        type: 'GET',
        url: base_url + 'role_menu/getRoleMenu',
        async: true,
        dataType: 'json',
        success: function(data, text) {
            var isActive = 'Aktif';
            var action = '';
            for (var i = 0; i < data.length; i++) {
                (data[i].active_stat == 1) ? isActive = '<button value="1" onclick="changeStat(' + data[i].id_role_menu + ',this.value)" id="act-rolemenu" title="Klik untuk nonaktifkan" class="btn btn-minier btn-primary" >Aktif</button>': isActive = '<button value="0" onclick="changeStat(' + data[i].id_role_menu + ',this.value)" id="act-rolemenu" title="Klik untuk aktifkan" class="btn btn-minier btn-danger" >Tidak Aktif</button>';
                action = '\
					<td>\
					<div class="hidden-sm hidden-xs btn-group">\
					<button title="Edit" class="btn btn-xs btn-info" onclick="editRoleMenu(' + data[i].id_role_menu + ')">\
					<i class="ace-icon fa fa-pencil bigger-120"></i>\
					</button>\
					\
					<button title="Hapus" class="btn btn-xs btn-danger" onclick="deleteRoleMenu(' + data[i].id_role_menu + ',\'' + data[i].title + '\')">\
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
					<a onclick="editRoleMenu(' + data[i].id_role_menu + ')" class="tooltip-success" data-rel="tooltip" title="" data-original-title="Edit">\
					<span class="green">\
					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>\
					</span>\
					</a>\
					</li>\
					\
					<li>\
					<a onclick="deleteRoleMenu(' + data[i].id_role_menu + ',\'' + data[i].title + '\')" class="tooltip-error" data-rel="tooltip" title="" data-original-title="Hapus">\
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
                tableRoleMenu.row.add([
                    (i + 1), data[i].hak_akses, data[i].title, isActive, action
                ]);
            }
            tableRoleMenu.draw();
        },
        error: function(request, status, error) {
            alert('Data role menu gagal dimuat!');
        }
    });
}

function changeStat(id_data, current_stat) {
    $.ajax({
        type: 'POST',
        url: base_url + 'role_menu/changeActiveStat',
        data: {
            id: id_data,
            stat: current_stat
        },
        dataType: 'json',
        success: function(data, text) {
            if (data.success == true) {
                bootbox.dialog({
							closeButton:false,
							message: "<span class='bigger-110'>Data role menu berhasil diubah!</span>",
							buttons: 			
							{
								"click" :
								{
									"label" : "OK",
									"className" : "btn-sm btn-primary",
									"callback": function() {
                        			document.location.href = base_url + 'role_menu';
									}
								}
							}
				});
            } else {
                bootbox.dialog({
							closeButton:false,
							message: "<span class='bigger-110'>Data role menu gagal diubah!</span>",
							buttons: 			
							{
								"click" :
								{
									"label" : "OK",
									"className" : "btn-sm btn-primary",
									"callback": function() {}
								}
							}
				});
            }
        },
        error: function(req, stat, error) {
            alert('error ' + error);
        }
    });
}

function deleteRoleMenu(id, title) {
    bootbox.confirm({
        message: "Apabila menu menjadi parent untuk menu lain, maka menu dibawahnya tidak dapat diakses, Anda yakin ingin menghapus role menu " + title + " ?",
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
            if (result === true) {
                $.ajax({
                    type: 'POST',
                    url: base_url + 'role_menu/delete',
                    data: {
                        id_role_menu: id
                    },
                    dataType: 'json',
                    success: function() {
                       bootbox.dialog({
							closeButton:false,
							message: "<span class='bigger-110'>Data role menu "+title+" berhasil dihapus!</span>",
							buttons: 			
							{
								"click" :
								{
									"label" : "OK",
									"className" : "btn-sm btn-primary",
									"callback": function() {
                        			document.location.href = base_url + 'role_menu';
									}
								}
							}
						});
                    },
                    error: function() {
                    	bootbox.dialog({
							closeButton:false,
							message: "<span class='bigger-110'>Data role menu "+title+" gagal dihapus!</span>",
							buttons: 			
							{
								"click" :
								{
									"label" : "OK",
									"className" : "btn-sm btn-primary",
									"callback": function() {}
								}
							}
						});
                    }
                });
            } else {}
        }
    });
}

function editRoleMenu(id)
{
	$.redirect(base_url+'role_menu/edit',{id_role_menu:id},'POST');
}