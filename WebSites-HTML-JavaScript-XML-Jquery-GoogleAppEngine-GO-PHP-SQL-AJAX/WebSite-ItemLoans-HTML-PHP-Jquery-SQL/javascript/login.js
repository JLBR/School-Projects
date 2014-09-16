// this should really be an immediately executable function in 
// order to abstract away the user's credentials from global scope

var uservm = {
	username: ko.observable(''),
	password: ko.observable(''),

	login_failed: ko.observable(false),

	login: function() {
		var self = this;

		$.ajax({
			type: "POST",
			url: 'auth/login.php',
			data: {
				user: self.username(),
				pass: self.password()
			}
		}).done(function(data){
			data = JSON.parse(data);

			if (data.success === true) {
				//login successful, move on to the inventory page
				window.location.replace('inventory.html');
			} else {
				//login failed, notify the user.
				self.login_failed(true);
			}
		});
	}
};

ko.applyBindings(uservm);