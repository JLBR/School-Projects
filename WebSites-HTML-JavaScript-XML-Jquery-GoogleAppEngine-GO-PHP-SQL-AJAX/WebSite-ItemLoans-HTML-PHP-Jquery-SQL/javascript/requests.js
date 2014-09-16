function buildItem(data) {
	data = data || {};

	return {
		id: ko.observable(data['id'] || ''),
		name: ko.observable(data['name'] || ''),
		item: ko.observable(data['item'] || ''),
		username: ko.observable(data['username'] || ''),
		borrower: ko.observable(data['borrower'] || ''),
		borrowdate: ko.observable(data['borrowdate'] || ''),
		returndate: ko.observable(data['returndate'] || ''),
		action: ko.observable(data['action'] || ''),
		comments: ko.observableArray([]),
		newcomment: ko.observable(''),
	
		selected: ko.observable(false),
		toggleme: function() {
			this.selected(!this.selected());
		},
		acceptLoan: function() {
			this.addComment('accepted');
		},
		rejectLoan: function() {
			this.addComment('rejected');
		},
		pendLoan: function() {
			this.addComment('pending');
		},
		addComment: function(action) {
			var self = this;
			$.ajax({
				type: "POST",
				url: 'requests/edit.php',
				data: {
					id: self.id(),
					borrower: self.borrower(),
					item: self.item(),
					action: action
				}
			}).done(function(data){
				data = JSON.parse(data);

				if (data.success === true) {
					// update status successful
					self.action(action);		
					self.postComment();
				} else {
					//delete failed, notify the user.
					alert("Could not update status: ", data.data);
				}
			});
		},
		postComment: function() {
			var self = this;

			// add the comment and crap.
			var comment = {
				fname: '',
				lname: "me",
				date: new Date(),
				comment: this.newcomment()
			};

			$.ajax({
				type: "POST",
				url: 'requests/comments/write.php',
				data: {
					comment: comment.comment,
					request: self.id
				}
			}).done(function(data){
				data = JSON.parse(data);

				if (data.success === true) {
					//write successful, write it.
					self.comments.push(buildComment(comment));
					self.newcomment('');
				} else {
					//delete failed, notify the user.
					alert("Could not write comment: ", data.data);
				}
			});
		}
	};
}

function buildComment(data) {
	return {
		comment: ko.observable(data.comment),
		date: ko.observable(data.date),
		username: ko.observable(data.fname + ' ' + data.lname)
	};
}

var incoming = ko.observableArray([]);
var outgoing = ko.observableArray([]);

function buildRequestAndComments(data, target) {
	var d = JSON.parse(data);

	for (var i = 0, len = d.length; i < len; i++) {
		var item = d[i];
		item.username = item.fname + ' ' + item.lname;

		var itemObservable = buildItem(item);
		target.push(itemObservable);

		buildItemComments(itemObservable);
	}
}

function buildItemComments(item) {
	$.ajax("requests/comments/get.php?request=" + item.id()).done(function(commentsData) {
		var c = JSON.parse(commentsData);

		for (var j = 0, len = c.length; j < len; j++) {
			item.comments.push(buildComment(c[j]));
		}
	});
}

$.ajax("requests/get.php?direction=incoming").done(function(data) {
	buildRequestAndComments(data, incoming);
});

$.ajax("requests/get.php?direction=outgoing").done(function(data) {
	buildRequestAndComments(data, outgoing);
});

var vm = {
	incoming: incoming,
	outgoing: outgoing,
};

ko.applyBindings(vm);