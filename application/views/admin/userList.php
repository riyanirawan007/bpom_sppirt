<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>User List</title>
<style type="text/css">
	nav ul li{
		display				: inline;
		list-style-type	: circle;
		margin-right		: 14px;	
	}
	nav ul li:last-child{
		margin-right		: 0;	
	}
</style>

<script>
function delete_valid(link){
	
	var r = confirm("Apakah Anda yakin?");
		if (r == true) {
			window.location.href = '<?= base_url() ?>'+link+'';
		} else {
			
		}
	}
</script>

</head>

<body>
	<h1>Daftar User</h1>
	<hr>
	<?php $no=1; ?>
    <?= @$this->session->flashdata('message') ?>
    <?= @$this->session->flashdata('errors') ?>
        <table class="table table-bordered">
        	<thead>
            	<th>No</th>
                <th>Username</th>
                <th>Password</th>
                <th>Hak Akses</th>
                <th>Email</th>
                <th>Aksi</th>
            </thead>
            <?php foreach($result as $data): ?>
            	<tr>
                	<td><?= $no++ ?></td>
                    <td><?= $data->username ?></td>
                    <td><?= $data->password ?></td>
                    <td><?= $data->hak_akses ?></td>
                    <td><?= $data->email ?></td>
                    <td><?= anchor('home/editUser/'.$data->id_user, 'Ubah') ?> | <?= '<a onclick=delete_valid("home/delUser/'.$data->id_user.'")>Hapus</a>' ?></td>
                </tr>
            <?php endforeach ?>
        </table>
        
</body>
</html>