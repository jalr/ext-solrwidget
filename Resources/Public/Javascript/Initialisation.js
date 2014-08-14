setTimeout(function() {
solrwidget = {
	displayResults: function(results, element) {
		var resultData = [];
		var field = element.find('input[name="query"]');
		field.popover('destroy');
		field.tooltip('destroy');
		if (0 == results.length) {
			field.tooltip({title: 'No results', trigger: 'manual', placement: 'bottom'}).tooltip('show');
			return;
		};
		for (var i in results) {
			var result = results[i];
			var template = $(element.find('.result-template').html());
			if (0 < result.teaser.length) {
				template.find('.teaser').html(result.teaser);
			} else {
				template.find('.teaser').remove();
			};
			template.find('.result-title').html(result.title);
			template.find('.url').attr('href', result.url);
			resultData.push(template.html());
		};
		var popover = {
			placement: 'bottom',
			content: '<ol><li>' + resultData.join("</li><li>") + '</li></ol>',
			html: true,
			trigger: 'manual'
		};
		if (element.attr('data-result-label')) {
			popover.title = element.attr('data-result-label') + ' ' + resultData.length;
		};
		field.popover(popover).popover('show');
	},
	submit: function(formatter, element) {
		var url = element.attr('action');
		var field = element.find('input[name="query"]');
		var query = field.val();
		var submit = element.find('button, input[type="submit"]');
		url = url.replace('%23%23%23QUERY%23%23%23', query);
		url = url.substr(0, url.indexOf('&cHash='));
		field.parent().addClass('info');
		submit.addClass('btn-info').button('loading');
		document.location.hash = query;
		$.getJSON(url, function(response) {
			field.parent().removeClass('info');
			submit.removeClass('btn-info').button('reset');
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
			formatter.call(this, response, element);
		})
		return false;
	}
};
jQuery(document).ready(function($) {
	$('.solrwidget-form').each(function() {
		var element = $(this);
		var formatter = element.attr('data-result-formatter');
		var namespaces = formatter.split(".");
		var context = window;
		var func = namespaces.pop();
		for(var i = 0; i < namespaces.length; i++) {
			context = context[namespaces[i]];
		};
		var submit = element.find('button, input[type="submit"]');
		var field = element.find('input[name="query"]');
		var timer;
		element.bind('submit', function() {
			return window.solrwidget.submit(context[func], element);
		});
		element.bind('keyup', function() {
			if (event.keyCode) {
				var character = String.fromCharCode(event.keyCode);
				var isWordcharacter = character.match(/\w/);
				var triggerKeys = [8, 91, 13, 46];
				if (!isWordcharacter && 0 > triggerKeys.indexOf(event.keyCode)) {
					return;
				};
			};
			clearTimeout(timer);
			timer = setTimeout(function() {
				window.solrwidget.submit(context[func], element)
			}, 250);
			return false;
		});
		if (document.location.hash) {
			field.attr('value', document.location.hash.substring(1));
			$(this).submit();
			return false;
		};
	});
});
}, 500);
