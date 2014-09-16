	// Performs the search.
	function search(query, serviceOp)
	{
		
		// Establish the data to pass to the proxy.
		var data = { q: query, sop: serviceOp, market: 'en-us' };
		// Calls the proxy, passing the query, service operation and market.
		$.getJSON('./Bing/bing_proxy.php', data, function(obj)
		{
			if (obj.d !== undefined)
			{
				$('#news').empty();
				var items = obj.d.results;
				var len = (items.length>5)? 5: items.length;
				for (var k = 0, len; k < len; k++)
				{
					var item = items[k];
					switch (item.__metadata.type)
					{
						case 'NewsResult':
							showNewsResult(item);
							break;
					}
				}
			}
		});
	}

	// Shows one item of Web result.
	function showNewsResult(item)
	{
		var p = document.createElement('p');
		var d = document.createElement('p');
		var a = document.createElement('a');
		a.href = item.Url;
		$(a).append(item.Title);
		$(d).append(item.Date);
		$(p).append(item.Description);
		// Append the anchor tag and paragraph with the description to the results div.
		$('#news').append(a, d, p);
	}