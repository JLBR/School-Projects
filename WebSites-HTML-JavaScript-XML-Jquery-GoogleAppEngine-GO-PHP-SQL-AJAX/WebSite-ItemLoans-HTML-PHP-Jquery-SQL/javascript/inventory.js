function buildItem(data) {
	data = data || {};

	return {
		id: ko.observable(data['id'] || ''),
		name: ko.observable(data['name'] || ''),
		owner: ko.observable(data['owner'] || ''),
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
		history: ko.observableArray([]),
		show_history: ko.observable(false),
		loadme: function() {
			vm.activeItem(this);
		},
		togglehistory: function() {
			this.show_history(!this.show_history());
		},
		deleteme: function() {
			var self = this;

			$.ajax({
				type: "POST",
				url: 'inventory/delete.php',
				data: {
					id: self.id()
				}
			}).done(function(data){
				data = JSON.parse(data);

				if (data.success === true) {
					//delete successful, hide it.
					vm.items.splice(vm.items.indexOf(this), 1);
				} else {
					//delete failed, notify the user.
					alert("Could not delete item: ", self.name());
				}
			});
		}

	};
}

var items = ko.observableArray([]);

var vm = {
	items: items,
	activeItem: ko.observable(buildItem()),
	submitItem: function() {
	var self = vm.activeItem();

		var post_data = {
				name: self.name(),
				owner: self.owner(),
				category: self.category(),
				serialnum: self.serialnum(),
				description: self.description(),
				features: self.features(),
				accessories: self.accessories(),
				pages: self.pages(),
				os: self.os(),
				notes: self.notes(), // lolwut?
				status: self.status(),  // lolwut?
				loanable: self.loan_toggle() === "Active" ? 1 : 0,
			};

		if (self.id() !== '') post_data.id = self.id();

		$.ajax({
			type: "POST",
			url: 'inventory/edit.php',
			data: post_data
		}).done(function(data){
			data = JSON.parse(data);

			if (data.success === true) {
				//add successful, hide it.
				vm.activeItem().id(data.data.id);
				if (items.indexOf(vm.activeItem()) < 0) items.push(vm.activeItem());
				vm.resetItem();
			} else {
				//add failed, notify the user.
				alert("Could not add item: ", self.name());
			}
		});
	},
	resetItem: function() {
		vm.activeItem(buildItem());
	}
};

function loadPage(){


	$.ajax("inventory/get.php").done(function(data) {
		var d = JSON.parse(data);

		for (var i = 0, len = d.length; i < len; i++) {
			var item = buildItem(d[i]);
			items.push(item);
			getItemHistory(item);
	}
	});

	function getItemHistory(item) {
		$.ajax("loans/history.php?id=" + item.id()).done(function(data) {
			var d = JSON.parse(data);

			for (var i = 0, len = d.length; i < len; i++) {
				item.history.push(d[i]);
			}
		});	
	}



	ko.applyBindings(vm);

}

function showResults(data, info){

	var d = JSON.parse(data);
	ko.cleanNode("#itemsList");
	items.removeAll();
	for (var i = 0, len = d.length; i < len; i++) {
		items.push(buildItem(d[i]));
	}
}

function errorFunction(jqXHRobj, textStatus, errorThrown){
	$("#errorMessage").text("error occurred: "+jqXHRobj.status+" "+textStatus +" "+errorThrown);
}

$(document).ready(function() {
	loadPage();

	$("#search").on('click', function(e) {
		var data = { item: $("#searchTerm").val() };

			$.ajax({
				type: "post",
				url: 'scripts/searchOwner.php',
				data: data,
				success:showResults,
				error:errorFunction
			});
	});

});