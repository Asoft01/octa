;(function($, window, document, undefined) {

	var pluginName = 'infinite';

	var defaults = {
		bottomBuffer: 80,
		classPrefix: pluginName,
		debounceInt: 100,
		eventPrefix: pluginName,
		formData: null,
		initialLoad: true,
		initialPage: 1,
		loader: '',
		pageParameter: 'page',
		params: {},
		paused: false,
		requestMethod: 'GET',
		url: null
	};

	function Plugin(element, options) {
		this._defaults = defaults;
		this._name = pluginName;
		this.config = $.extend({}, defaults, options);
		
		this.$el = $(element);
		this.$loader = $(this.config.loader);
		this.$scroll = this.getScrollContainer();

		this.baseclass = element.getAttribute('class').trim();
		this.finished = false;
		this.loading = false;
		this.page = this.config.initialPage;

		this.states = {
			empty: this.config.classPrefix + '-state-empty',
			error: this.config.classPrefix + '-state-error',
			finished: this.config.classPrefix + '-state-finished',
			loading: this.config.classPrefix + '-state-loading',
			paused: this.config.classPrefix + '-state-paused'
		};

		if (this.config.initialLoad) {
			this.paused = false;
			this.$el.addClass(this.states.empty);
			this.createLoader();
			this.loadMore(function(self) {
				self.paused = self.config.paused;
				if (self.paused) self.$el.addClass(self.states.paused);
			});
		} else {
			this.paused = this.config.paused;
			this.$el.addClass(this.paused ? [this.states.empty, this.states.paused] : this.states.empty);
			this.createLoader();
		}

		this.createEventListeners();
	};

	Plugin.prototype.destroy = function(removeContent = false, removeLoader = true) {
		var self = this.$el;
		clearInterval(this.interval);
		if (removeContent) { this.$el.empty(); }
		if (removeLoader) { this.$loader.empty(); }
		this.$el.attr('class', this.baseclass).removeData(this._name);
		return self;
	};

	Plugin.prototype.createEventListeners = function() {
		var self = this;
		this.interval = window.setInterval(_.debounce(function() { self.handleScroll(); }, this.config.debounceInt), 500);
		//this.$scroll.on('scroll.' + this._name, _.debounce(function() { self.handleScroll(); }, this.config.debounceInt));
		return this;
	};

	Plugin.prototype.createLoader = function() {
		this.$loader.attr('style', 'opacity: 0; speak: none;').html([
			'<div class="d-flex justify-content-center">',
				'<div class="spinner-border text-muted" role="status">',
					'<span class="sr-only">Loading...</span>',
				'</div>',
			'</div>'
		].join(''));
	};

	Plugin.prototype.getElementBottom = function() {
		return (this.$el == this.$scroll) ? this.$el[0].scrollHeight : (this.$el.height() + this.$el.offset().top);
	};

	Plugin.prototype.getScrollBottom = function() {
		return this.$scroll.scrollTop() + this.$scroll.height() + this.config.bottomBuffer;
	};

	Plugin.prototype.getScrollContainer = function() {
		var isScrollable = function($el) {
			return (/(auto|scroll)/).test($el.css('overflow') + $el.css('overflow-y'));
		};

		if (isScrollable(this.$el)) return this.$el;

		$parents = this.$el.parents().filter(function() {
			return isScrollable($(this));
		});

		if ($parents.length > 0) return $parents;

		return $(window);
	};

	Plugin.prototype.handleScroll = function() {
		if (this.shouldTriggerLoad()) this.loadMore(null);
		return this;
	};

	Plugin.prototype.shouldTriggerLoad = function() {
		return (!this.loading && !this.paused && !this.finished && this.getScrollBottom() >= this.getElementBottom() && this.$el.is(':visible'));
	};

	Plugin.prototype.loadMore = function(callback) {
		var self = this;
		var params = $.extend({}, this.config.params);
		params[this.config.pageParameter] = this.page;

		this.loading = true;
		this.$el.addClass(this.states.loading);
		this.$loader.attr('style', '');

		options = {
			method: this.config.requestMethod,
			params: params,
			url: this.config.url
		};

		if (this.config.formData) {
			options.data = this.config.formData;
			options.headers = {"Content-Type": "multipart/form-data"};
			var entries = [];
			for (var pair of options.data.entries()) {
				entries.push({
					key: pair[0],
					value: pair[1]
				});
			}
		}

		axios.request(options)
			.then(function(response) {
				if (response.data.page >= response.data.pages) {
					self.$el.addClass(self.states.finished);
					self.finished = true;
				}
				self.$el.removeClass([self.states.empty, self.states.loading]);
				self.$loader.attr('style', 'opacity: 0; speak: none;');
				self.$el.append(response.data.html);
				self.page = response.data.page + 1;
				if (_.isFunction(callback)) callback(self);
				self.loading = false;
				self.$el.trigger('infinite-loaded', response);
			})
			.catch(function(error) {
				self.$el.removeClass(self.states.loading).addClass([self.states.finished, self.states.error]);
				self.$loader.attr('style', 'opacity: 0; speak: none;');
				self.finished = true;
				self.loading = false;
				console.log(error);
			});

		return this;
	};

	Plugin.prototype.pause = function() {
		this.paused = true;
		this.$el.addClass(this.states.paused);
		return this;
	};

	Plugin.prototype.unpause = function() {
		this.paused = false;
		this.$el.removeClass(this.states.paused);
		return this;
	};

	$.fn[pluginName] = function(options) {
		return this.each(function() {
			if (!$.data(this, pluginName)) {
				$.data(this, pluginName, new Plugin(this, options));
			}
		});
	};

})(jQuery, window, document);