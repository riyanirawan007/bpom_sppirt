jQuery(function(){
	jQuery('.status_pengajuan').change(function(e, val){
		var chosen = jQuery(this).val();
		if(chosen == 'baru'){
			jQuery('#no-pirt-field').val("").slideUp();
		}else{
			jQuery('#no-pirt-field').slideDown();
		}
	});
});