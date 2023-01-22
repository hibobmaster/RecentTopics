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

RecentTopics.askForWrite = function () {
	'use strict';

	if ($('input[name="rt_reset_default"]').prop('checked')) {
		window.scrollTo(0, 0);
		$('#ask_before_submit').css('display', '');
		$('#acp_board').css('display', 'none');
	} else {
		$('#submit').click();
	}
}

RecentTopics.abortWrite = function () {
	'use strict';

	$('input[name="rt_reset_default"]').prop('checked', false);
	$('#ask_before_submit').css('display', 'none');
	$('#acp_board').css('display', '');
}

RecentTopics.writeData = function () {
	'use strict';

	$('#submit').click();
}
