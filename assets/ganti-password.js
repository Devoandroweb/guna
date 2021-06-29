$(document).ready(function () {
	$('#password_lama').keyup(function(event) {
			var pass = $(this).val();
			console.log(pass);
			$.ajax({
				url: '<?= base_url() ?>cdashboard/get_old_pass',
				type: 'POST',
				dataType: 'json',
				data : {password_lama:pass},
			})
			.done(function(data) {
				console.log(data['kode']);
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
			

	});
});