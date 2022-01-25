;(function($, window, document, undefined) {

	var pluginName = 'uploader';

	var defaults = {
		oldValue: null,
		classPrefix: 'uploader',
		eventPrefix: 'aup',
		buttonText: 'Browse',
		labelText: 'No file selected',
		successIcon: 'fas fa-check-circle',
		failureIcon: 'fas fa-exclamation-triangle',
		url: null,
		headers: {
			'Content-Type': 'multipart/form-data'
		}
	};

	function Plugin(element, options) {
		this.config = $.extend({}, defaults, options);
		this.element = element;
		this._defaults = defaults;
		this._name = pluginName;
		this.init();
	};

	Plugin.prototype.init = function() {
		this.injectHtml(this.element);
		this.$el = $(this.element);
		this.$container = this.$el.closest('.' + this.config.classPrefix + '-container');
		this.events = {
			change: this.config.eventPrefix + '-change',
			success: this.config.eventPrefix + '-success',
			failure: this.config.eventPrefix + '-failure',
			started: this.config.eventPrefix + '-started',
			finished: this.config.eventPrefix + '-finished',
			progress: this.config.eventPrefix + '-progress'
		};
		this.states = {
			empty: this.config.classPrefix + '-state-empty',
			filled: this.config.classPrefix + '-state-filled',
			failure: this.config.classPrefix + '-state-failure',
			success: this.config.classPrefix + '-state-success'
		};
		if (this.config.oldValue) {
			this.find('label').text(this.config.oldValue.split(/[\\/]/).pop());
			this.find('textinput').val(this.config.oldValue);
			this.find('indicator').attr('title', 'Already Uploaded');
			this.setState('success');
			this.setPercentage(100);
		}
		this.bindEventListeners();
	};

	Plugin.prototype.injectHtml = function(element) {
		var text_input_name = element.hasAttribute('name') ? element.getAttribute('name') : '';
		var file_input_old_id = element.hasAttribute('id') ? element.getAttribute('id') : '';
		var file_input_id = this.config.classPrefix + '-' + Math.floor(Math.random() * 1000000);

		$('label[for="' + file_input_old_id + '"]').attr('for', file_input_id);

		html_wrapper = [
			'<div class="' + this.config.classPrefix + ' ' + this.config.classPrefix + '-container">',
				'<div class="' + this.config.classPrefix + '-group input-group">',
					'<div class="' + this.config.classPrefix + '-wrapper custom-file">',
					'</div>',
				'</div>',
			'</div>'
		].join('');

		html_wrapper_prepend = [
			'<input class="' + this.config.classPrefix + '-textinput" name="' + text_input_name + '" type="hidden">',
			'<div class="' + this.config.classPrefix + '-progress progress">',
				'<div class="' + this.config.classPrefix + '-progressbar progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>',
			'</div>'
		].join('');

		html_wrapper_append = [
			'<label for="' + file_input_id + '" class="' + this.config.classPrefix + '-label custom-file-label text-truncate">' + this.config.labelText + '</label>',
			'<div class="' + this.config.classPrefix + '-indicator">',
				'<i class="' + this.config.classPrefix + '-indicator-failure ' + this.config.failureIcon + '"></i>',
				'<i class="' + this.config.classPrefix + '-indicator-success ' + this.config.successIcon + '"></i>',
			'</div>'
		].join('');

		html_wrapper_after = [
			'<div class="' + this.config.classPrefix + '-append input-group-append">',
				'<label for="' + file_input_id + '" class="' + this.config.classPrefix + '-button btn btn-primary">' + this.config.buttonText + '</label>',
			'</div>'
		].join('');

		$(element)
			.attr('id', file_input_id)
			.removeAttr('name')
			.addClass(this.config.classPrefix + '-fileinput custom-file-input')
			.wrap(html_wrapper)
			.parent()
			.prepend(html_wrapper_prepend)
			.append(html_wrapper_append)
			.after(html_wrapper_after);
	};

	Plugin.prototype.bindEventListeners = function() {
		var _this = this;

		this.$el.on('change', function(originalEvent) {
			_this.$el.trigger(_this.events.change, originalEvent);
		});

		this.$el.on(_this.events.change, function(event, originalEvent) {
			_this.setPercentage(0, false);			
			if (_this.element.files.length) {
				_this.find('textinput').val('');
				_this.find('label').text(_this.element.files[0].name);
				_this.setState('filled');
				_this.upload();
			} else {
				_this.find('textinput').val('');
				_this.find('label').text(_this.config.labelText);
				_this.setState('empty');
			}
		});

		this.$el.on(_this.events.progress, function(event, originalEvent) {
			_this.setPercentage( Math.round((originalEvent.loaded * 100) / originalEvent.total) );
		});

		this.$el.on(_this.events.success, function(event, response) {			
			if (typeof response.data === 'object' && response.data !== null) {
				_this.$el.val('');
				_this.find('textinput').val(response.data.success[0].filepath);
				_this.find('indicator').attr('title', 'File Uploaded');
				_this.setState('success');
			} else {
				_this.$el.trigger(_this.events.failure, null);
			}
		});

		this.$el.on(_this.events.failure, function(event, error) {		
			var message = error ? error.response.statusText : 'Unknown Error';
			_this.$el.val('');
			_this.find('textinput').val('');
			_this.find('indicator').attr('title', message);
			_this.setState('failure');
		});
	};

	Plugin.prototype.find = function(name) {
		var selector = '.' + this.config.classPrefix + '-' + name;
		return this.$container.find(selector);
	};

	Plugin.prototype.setPercentage = function(percent = 0, transition = true) {
		if (transition) {
			this.find('progressbar')
				.queue(function(next) {
					$(this).attr('aria-valuenow', percent).css('width', percent + '%');
					next();
				});
		} else {
			var prefix = this.config.classPrefix;
			this.find('progressbar')
				.queue(function(next) {
					$(this).addClass(prefix + '-notransition');
					next();
				})
				.delay(100)
				.queue(function(next) {
					$(this).attr('aria-valuenow', percent).css('width', percent + '%');
					next();
				})
				.delay(100)
				.queue(function(next) {
					$(this).removeClass(prefix + '-notransition');
					next();
				});
		}
		return this;
	};

	Plugin.prototype.setState = function(state) {
		var classlist = this.config.classPrefix + ' ' + this.config.classPrefix + '-container ' + this.states[state];
		this.$container.attr('class', classlist);
		return this;
	};

	Plugin.prototype.upload = function() {
		var _this = this;
		formdata = new FormData();
		formdata.append('file', this.element.files[0]);
		window.axios
			.post(this.config.url, formdata, {
				headers: this.config.headers,
				onUploadProgress: function(originalEvent) {
					_this.$el.trigger(_this.events.progress, originalEvent);
				}
			})
			.then(function(response) {
				_this.$el.trigger(_this.events.success, response);
			})
			.catch(function(error) {
				_this.$el.trigger(_this.events.failure, error);
			})
			.then(function() {
				_this.$el.trigger(_this.events.finished);
			});
		this.$el.trigger(_this.events.started);
	};

	$.fn[pluginName] = function(options) {
		return this.each(function() {
			if (!$.data(this, pluginName)) {
				$.data(this, pluginName, new Plugin(this, options));
			}
		});
	};

})(jQuery, window, document);