/**
 *
 * Recent Topics. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2022, IMC, https://github.com/IMC-GER / LukeWCS, https://github.com/LukeWCS
 * @copyright (c) 2017, Sajaki, https://www.avathar.be
 * @copyright (c) 2015, PayBas
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 */

var RecentTopics = {};

(function () {	// IIFE start

'use strict';

class LukeWCSphpBBConfirmBox {
/*
* phpBB ConfirmBox class for checkboxes - v1.3.0
* @copyright (c) 2023, LukeWCS, https://www.wcsaga.org
* @license GNU General Public License, version 2 (GPL-2.0-only)
*/
	constructor(submitSelector, animDuration = 0) {
		this.$submitObject = $(submitSelector);
		this.$formObject = this.$submitObject.parents('form');
		this.animDuration = animDuration;
		var _this = this;

		this.$formObject.find('div[id$="_confirmbox"]').each(function () {
			var elementName = this.id.replace('_confirmbox', '');

			$('input[name="' + elementName + '"]')				.on('change'	, _this.#Show);
			$('input[name^="' + elementName + '_confirm_"]')	.on('click'		, _this.#Button);
		});
		this.$formObject										.on('reset'		, _this.HideAll);
	}

	#Show = (e) => {
		var $elementObject		= $('input[name="' + e.target.name + '"]');
		var $confirmBoxObject	= $('div[id="' + e.target.name + '_confirmbox"]');

		if ($elementObject.prop('checked') != $confirmBoxObject.attr('data-default')) {
			this.#changeBoxState($elementObject, $confirmBoxObject, true);
		}
	}

	#Button = (e) => {
		var elementName			= e.target.name.replace(/_confirm_.*/, '');
		var $elementObject		= $('input[name="' + elementName + '"]');
		var $confirmBoxObject	= $('div[id="' + elementName + '_confirmbox"]');

		if (e.target.name.endsWith('_confirm_no')) {
			$elementObject.prop('checked', $confirmBoxObject.attr('data-default'));
		}

		this.#changeBoxState($elementObject, $confirmBoxObject, null);
	}

	HideAll = () => {
		var $elementObject		= this.$formObject.find('input.confirmbox_active');
		var $confirmBoxObject	= this.$formObject.find('div[id$="_confirmbox"]');

		this.#changeBoxState($elementObject, $confirmBoxObject, false);
	}

	#changeBoxState = ($elementObject, $confirmBoxObject, showBox) => {
		$elementObject		.prop('disabled', !!showBox);
		$elementObject		.toggleClass('confirmbox_active', !!showBox);
		$confirmBoxObject	[!!showBox ? 'show' : 'hide'](this.animDuration);
		this.$submitObject	.prop('disabled', showBox ?? this.$formObject.find('input.confirmbox_active').length);
	}
}

// Register events

$(window).ready(function() {
	RecentTopics.ConfirmBox = new LukeWCSphpBBConfirmBox('input[name="submit"]', 300);
});

})();	// IIFE end
