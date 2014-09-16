function buildItem(data) {
	data = data || {};

	return {
		id: ko.observable(data['id'] || ''),
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

function loadPage(){

        $.ajax("loans/get.php").done(function(data) {
	        var d = JSON.parse(data);

	        for (var i = 0, len = d.length; i < len; i++) {
		        loanables.push(buildItem(d[i]));
	        }
        });

        $.ajax("loans/get.php?to=others").done(function(data) {
	        var d = JSON.parse(data);

	        for (var i = 0, len = d.length; i < len; i++) {
		        d[i].borrower = d[i].fname + ' ' + d[i].lname;
		        myloaned.push(buildItem(d[i]));
	        }
        });

        $.ajax("loans/get.php?to=me").done(function(data) {
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
}


function errorFunction(jqXHRobj, textStatus, errorThrown){
	$("#errorMessage").text("error occurred: "+jqXHRobj.status+" "+textStatus +" "+errorThrown);
}

function showResults(data, info){
	var d = JSON.parse(data);
	ko.cleanNode("#itemsList");
	loanables.removeAll();
	for (var i = 0, len = d.length; i < len; i++) {
		loanables.push(buildItem(d[i]));
	}
}

$(document).ready(function() {
	loadPage();

	$("#search").on('click', function(e) {
		var data = { item: $("#searchTerm").val() };
			$.ajax({
				type: "post",
				url: 'scripts/search.php',
				data: data,
				success:showResults,
				error:errorFunction
			});
	});

});