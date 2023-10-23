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

if (typeof RecentTopics == "undefined")
{
	RecentTopics = {};
}

// Settings

RecentTopics.ConfirmBox = function (e) {
	'use strict';

	var defaultState = Boolean($('div[id="' + e.target.name + '_confirmbox"]').attr('data-default'));

	if ($('input[name="' + e.target.name + '"]')		.prop('checked') != defaultState) {
		$('input[name="' + e.target.name + '"]')		.prop('disabled', true)
		$('input[name="' + e.target.name + '"]')		.addClass('confirmbox_active');
		$('div[id="' + e.target.name + '_confirmbox"]')	.show();
		$('input[name="submit"]')						.prop('disabled', true);
	}
};

RecentTopics.ConfirmBoxButton = function (e) {
	'use strict';

	var elementName = e.target.name.slice(0, e.target.name.indexOf('_confirm_'));
	var defaultState = Boolean($('div[id="' + elementName + '_confirmbox"]').attr('data-default'));

	if (e.target.name.endsWith('_no')) {
		$('input[name="' + elementName + '"]').prop('checked', defaultState);
	}

	$('input[name="' + elementName + '"]')			.prop('disabled', false);
	$('input[name="' + elementName + '"]')			.removeClass('confirmbox_active');
	$('div[id="' + elementName + '_confirmbox"]')	.hide();
	$('input[name="submit"]')						.prop('disabled', $('input[class*="confirmbox_active"]').length);
}

RecentTopics.ConfirmBoxHide = function () {
	'use strict';

	$('input[class*="confirmbox_active"]')	.prop('disabled', false);
	$('input[class*="confirmbox_active"]')	.removeClass('confirmbox_active');
	$('div[id$="_confirmbox"]')				.hide();
	$('input[name="submit"]')				.prop('disabled', false);
};

RecentTopics.FormReset = function () {
	'use strict';

	RecentTopics.ConfirmBoxHide();
};

// Register onChange and onClick events

$(window).ready(function() {
	'use strict';

	$('input[name="reset"]').on('click', RecentTopics.FormReset);

	$('div[id$="_confirmbox"]').each(function() {
		var elementName = $(this)[0].id.replace('_confirmbox', '')

		$('input[name="' + elementName + '"]')				.on('change', RecentTopics.ConfirmBox);
		$('input[name^="' + elementName + '_confirm_"]')	.on('click', RecentTopics.ConfirmBoxButton);
	});
});
