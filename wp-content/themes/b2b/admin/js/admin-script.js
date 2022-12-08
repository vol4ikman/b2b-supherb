// ADMIN scripts
jQuery(document).ready(function () {
	console.log("admin scripts");

	if (jQuery("#start_csv_import").length) {
		jQuery("#start_csv_import .acf-label").append(
			'<button type="button" id="start_import_from_b2c_file" class="button button-primary button-large">Start Import</button>'
		);

		jQuery("#start_import_from_b2c_file").click(function (e) {
			e.preventDefault();
			start_import_from_b2c();
		});
	}
});

function start_import_from_b2c() {
	jQuery.ajax({
		type: "post",
		dataType: "json",
		url: ajaxurl,
		data: {
			action: "start_import_from_b2c",
		},
		success: function (response) {
			console.log(response);
			if (response.error) {
				alert('Please Upload "Import CSV File" first.');
			} else {
			}
		},
	});
}
