setTimeout(function() {
jQuery(document).ready(function($) {
	$('.solrwidget-form').each(function() {
		var element = $(this);
		var submit = element.find('button, input[type="submit"]');
		var field = element.find('input[name="query"]');
		var url = element.attr('action');
		element.submit(function() {
			var query = field.val();
			url = url.replace('%23%23%23QUERY%23%23%23', query);
			$('.popover').remove();
			field.parent().addClass('info');
			submit.addClass('btn-info');
			document.location.hash = query;
			$.getJSON(url, function(response) {
				field.parent().removeClass('info');
				submit.removeClass('btn-info');
				if (response.error) {
					field.parent().addClass('error');
					submit.addClass('btn-danger');
					field.tooltip({
						title: response.error.message + ' (status: ' + response.error.status + ')',
						placement: 'bottom',
						trigger: 'manual'
					}).tooltip('show');
					return false;
				};
				displayResults(response);
			})
			return false;
		});
		var displayResults = function(results) {
			var resultData = [];
			for (var i in results) {
				resultHtml = buildResult(results[i]);
				console.log(resultHtml);
				resultData.push(resultHtml);
			};
			field.popover({
				title: 'Results: ' + resultData.length,
				placement: 'bottom',
				content: '<ol><li>' + resultData.join("</li><li>") + '</li></ol>',
				html: true,
				trigger: 'manual'
			}).popover('show');
		};
		var buildResult = function(result) {
			var template = $(element.find('.result-template').html());
			if (0 < result.teaser.length) {
				template.find('.teaser').html(result.teaser);
			} else {
				template.find('.teaser').remove();
			};
			template.find('.result-title').html(result.title);
			template.find('.url').attr('href', result.url);
			return template.html();
		};
		if (document.location.hash) {
			field.attr('value', document.location.hash.substring(1));
			$(this).submit();
			return false;
		};
	});
});
}, 1000);
