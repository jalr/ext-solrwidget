setTimeout(function() {
jQuery(document).ready(function($) {
	$('.solrwidget-form').each(function() {
		var element = $(this);
		var submit = element.find('button, input[type="submit"]');
		var url = element.attr('action');
		element.submit(function() {
			var query = element.find('input[name="query"]').val();
			url = url.replace('%23%23%23QUERY%23%23%23', query);
			$.getJSON(url, function(response) {
				console.log(response);
			})
			return false;
		});
	});
});
}, 1000);
