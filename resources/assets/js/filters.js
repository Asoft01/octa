const select2CustomMatcher = function(params, data) {
	var $parent = $(data.element.parentElement);
	var maximum = _.get($parent.data('select2').options.options, 'maximumResultsForSearch', null);

	if (data.element.index === 0) {
		$parent.data('select2ResultCounter', 0);
	}
	if (maximum === null) {
		return $.fn.select2.defaults.defaults.matcher(params, data);
	}
	if (typeof data.text === 'string') {
		var query = $.trim(params.term).toLowerCase();
		var content = $.trim(data.text.normalize('NFD').replace(/[\u0300-\u036f]/g, '')).toLowerCase();
		var results = $parent.data('select2ResultCounter');
			
		if (content.startsWith(query)) {
			$parent.data('select2ResultCounter', ++results);
			return (results <= maximum) ? data : null;
		}
	}

	return null;
};

const select2DelayClosing = function() {
	var $closing = $(this);
	if ($closing.hasClass('select2-closing-delayed') === false) {
		setTimeout(function() {
			$closing.addClass('select2-closing-delayed').select2('close');
		}, 100);
		return false;
	}
	$closing.removeClass('select2-closing-delayed');
	return true;
};

const select2TemplateResult = function(option) {
	return $('<span class="btn text-nowrap">' + option.text + '</span>');
};

const handleFormFilterDirectionToggles = function($el, $ol) {
	$el.addClass('focus');
	$ol.removeClass(['active', 'reverse', 'focus'])
		.find('input[type="checkbox"]').prop('checked', false).end()
		.find('input[type="hidden"]').prop('value', 'asc').end()
		.find('i').removeClass(['fa-arrow-up', 'fa-arrow-down']).addClass('fa-minus');

	if ($el.hasClass('active') && $el.hasClass('reverse')) {
		$el.removeClass(['active', 'reverse'])
			.find('input[type="checkbox"]').prop('checked', false).end()
			.find('input[type="hidden"]').prop('value', 'asc').end()
			.find('i').removeClass(['fa-arrow-up', 'fa-arrow-down']).addClass('fa-minus');
	}
	else if ($el.hasClass('active')) {
		$el.addClass('reverse')
			.find('input[type="checkbox"]').prop('checked', true).end()
			.find('input[type="hidden"]').prop('value', 'desc').end()
			.find('i').removeClass(['fa-minus', 'fa-arrow-down']).addClass('fa-arrow-up');
	}
	else {
		$el.addClass('active')
			.find('input[type="checkbox"]').prop('checked', true).end()
			.find('input[type="hidden"]').prop('value', 'asc').end()
			.find('i').removeClass(['fa-minus', 'fa-arrow-up']).addClass('fa-arrow-down');
	}
	return false;
};

$(document).ready(function() {

	$('select.select2filter')
		.data('select2ResultCounter', 0)
		.each(function(index, element) {
			$(element).select2({
				dropdownAutoWidth: false,
				dropdownParent: $(element).siblings('.select2-dropdown-parent').first(),
				matcher: select2CustomMatcher,
				maximumResultsForSearch: 10, // this isn't a real select2 option, but our custom matcher will use it
				//minimumResultsForSearch: 1,
				//minimumInputLength: 1,
				tags: false,
				templateResult: select2TemplateResult
			});
		})
		.on('select2:closing', select2DelayClosing)
		.on('select2:opening', function(event) {
			var $search = $(this).data('select2').$container.find('input.select2-search__field');
			if ($search.val().length < 1) {
				event.preventDefault();
				$search.focus();
				return false;
			}
		})
		.on('change', function(event) {
			var $select = $(this);
			var $buttons = $select.closest('.form-filters-select2').find('.btn[data-toggle="select"]');
			console.log(event, $select.val());
			$buttons.each(function(index, element) {
				var option = $(element).attr('data-select');
				if ($select.val().includes(option)) {
					$(element).addClass('active');
				} else {
					$(element).removeClass('active');
				}
			})
		});

	$(document).on('click', function(event) {
		var $target = $(event.target);
		var $buttons = $('#form-filters .btn-toggle-direction');

		if ($target.is($buttons)) {
			return handleFormFilterDirectionToggles($target, $buttons.not(event.target));
		} else {
			$buttons.removeClass('focus');
		}

		if (!$target.is('#topbar-filters-search, #topbar-filters-search *')) {
			if ($target.closest('#topbar-filters, #form-filters').length) return;
			if ($target.parent().addBack().hasClass('select2-selection__choice__remove')) return;
		}

		$('#form-filters').find('.collapse, .collapsing').collapse('hide');

	});
	
	$('.btn-clear-select2').on('click', function(event) {
		event.preventDefault();
		var $select = $(this).closest('.form-filters').find('select').first();
		$select.select2('close');
		$select.val(null).trigger('change');
	});

	$('.btn-clear-sorting').on('click', function() {
		$(this).closest('.form-filters')
			.find('.btn-toggle-direction').removeClass(['active', 'reverse', 'focus'])
			.find('input[type="checkbox"]').prop('checked', false).end()
			.find('input[type="hidden"]').prop('value', 'asc').end()
			.find('i').removeClass(['fa-arrow-up', 'fa-arrow-down']).addClass('fa-minus');
	});

	$('.btn-clear-type').on('click', function(event) {
		$(this).closest('.form-filters')
			.find('input[type="checkbox"]').prop('checked', false).end()
			.find('.btn-toggle').removeClass(['active', 'focus']);
	});

	$('#form-filters .btn[data-toggle="select"]').on('click', function() {
		var $button = $(this);
		var $select = $($button.attr('data-target'));
		var option = $button.attr('data-select');
		if ($select.val().includes(option)) {
			$select.val( _.without($select.val(), option) );
			//$button.removeClass('active');
		} else {
			$select.val( $select.val().concat([option]) );
			//$button.addClass('active');
		}
		$select.trigger('change');
		console.log($select.val());
	});

	$('#form-filters input.select2-search__field').on('propertychange input', function(event) {
		// The "propertychange" event exists in IE < 9. See: https://stackoverflow.com/a/17384341
		if (event.type === 'propertychange' && event.originalEvent.propertyName !== 'value') return;
		if ($(this).val().length < 1) {
			$(this).closest('.form-filters-select2').find('select.select2filter').addClass('select2-closing-delayed').select2('close');
		}
	});

	$('#form-filters')
		.on('submit', function(event) {
			event.preventDefault();
			if ($(event.target).attr('type') === 'button') return;

			var $header = $('#results-header');
			var $infinite = $('.infinite-scrolling');

			if ($infinite.data('infinite')) {
				$infinite.off().data('infinite').destroy(true, true);
				$header.find('#results-info').hide();
				$header.find('#results-total').html('0');
			}
			
			$(this).find('.collapse, .collapsing').collapse('hide');
			$(this).find('button[type="submit"]').removeAttr('disabled');

			$infinite
				.infinite({
					formData: new FormData(this),
					loader: '.infinite-loading',
					params: {
						show: 12
					},
					requestMethod: 'POST',
					url: $(this).attr('action')
				})
				.one('infinite-loaded', function(event, response) {
					$header.find('#results-total').html(response.data.total);
					$header.find('#results-info').show();
					if (response.data.total === 0) {
						$infinite.html([
							'<div class="col-12">',
								'<div class="alert alert-secondary text-light text-center bg-transparent mt-2" role="alert">',
									'<span>No results.</span>',
								'</div>',
							'</div>'
						].join(''));
					}
				});
		})
		.trigger('submit');

	$('.infinite-scrolling')
		.on('mouseenter', '.replaceImgVid', function() {
			var $video = $(this).find('video:first');
			if ($video.length) {
				$video[0].load();
				$video[0].play();
				$video.bind('playing', function() {
					$(this).css('display', '').parent().find('img').css('display', 'none');
				});
			}
		})
		.on('mouseleave', '.replaceImgVid', function() {
			var $video = $(this).find('video:first');
			if ($video.length) {
				$(this).find('img').css('display', '');
				$video.css('display', 'none')[0].pause();
			}
		});
});