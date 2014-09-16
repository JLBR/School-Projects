function buildItem(data) {
	data = data || {};

	return {
		id: ko.observable(data['loan_id'] || ''),
		name: ko.observable(data['name'] || ''),
		owner: ko.observable(data['owner'] || ''),
		borrower: ko.observable(data['borrower'] || ''),
		category: ko.observable(data['category'] || ''),
		serialnum: ko.observable(data['serialnum'] || ''),
		description: ko.observable(data['description'] || ''),
		features: ko.observable(data['features'] || ''),
		accessories: ko.observable(data['accessories'] || ''),
		pages: ko.observable(data['pages'] || ''),
		os: ko.observable(data['os'] || ''),
		notes: ko.observable(data['notes'] || ''),
		date: ko.observable(data['date'] || ''),
		status: ko.observable(data['status'] || ''),
		loan_toggle: ko.observable(data['loan_toggle']),
		borrowdate: ko.observable(''),
		returndate: ko.observable(''),
		date_out: ko.observable(data['date_out']),
		date_due: ko.observable(data['date_due']),
		selected: ko.observable(false),
		checkin: function() {
			var self = this;
			var post_data = {
				id: self.id()
			};

			$.ajax({
				type: "POST",
				url: 'loans3/edit.php',
				data: post_data
			}).done(function(data){
				data = JSON.parse(data);

				if (data.success === true) {
					//checkin successful, hide it.
					myloaned.remove(self);
				} else {
					//add failed, notify the user.
					alert("Could not add item: ", self.name());
				}
			});
		},
		loadme: function() {
			this.selected(!this.selected());
		},
		requestLoan: function() {
			var self = this;

			$.ajax({
				type: "POST",
				url: 'requests/edit.php',
				data: {
					item: self.id(),
					owner: self.owner(),
					borrowdate: self.borrowdate(),
					returndate: self.returndate()
				}
			}).done(function(data){
				data = JSON.parse(data);

				if (data.success === true) {
					// request successful
					loanables.remove(self);
				} else {
					//delete failed, notify the user.
					alert("Could not request item: ", self.name());
				}
			});
		}
	};
}

var loanables = ko.observableArray([]);
var myloaned = ko.observableArray([]);
var loanedtome = ko.observableArray([]);

$.ajax("loans3/get.php").done(function(data) {
	var d = JSON.parse(data);

	for (var i = 0, len = d.length; i < len; i++) {
		loanables.push(buildItem(d[i]));
	}
});

$.ajax("loans3/get.php?to=others").done(function(data) {
	var d = JSON.parse(data);

	for (var i = 0, len = d.length; i < len; i++) {
		d[i].borrower = d[i].fname + ' ' + d[i].lname;
		myloaned.push(buildItem(d[i]));
	}
});

$.ajax("loans3/get.php?to=me").done(function(data) {
	var d = JSON.parse(data);

	for (var i = 0, len = d.length; i < len; i++) {
		d[i].owner = d[i].fname + ' ' + d[i].lname;
		loanedtome.push(buildItem(d[i]));
	}
});

var vm = {
	loanables: loanables,
	myloaned: myloaned,
	loanedtome: loanedtome
};

ko.applyBindings(vm);