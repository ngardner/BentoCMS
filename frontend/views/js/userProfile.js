$(document).ready(function(){
	
	$('#userprofile_form').validate({
		rules: {
			user_email: {
				required: true,
				email: true
			},
			user_password: "required",
			user_password2: "required",
			user_company: "required",
			user_title: "required",
			user_fName: "required",
			user_lName: "required",
			user_phone: "required",
			user_address: "required",
			user_city: "required",
			user_province: "required",
			user_country: "required",
			user_zip: "required"
		}
	});
	
});