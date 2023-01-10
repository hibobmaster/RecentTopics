/**
 *
 * Recent Topics
 * An extension for the phpBB Forum Software package.
 * JavaScript for ACP
 *
 */

abortWrite();

function askForWrite() {
	if (document.getElementById('rt_reset_default').checked == true) {
		document.getElementById('ask_before_submit').style.display = 'block';
		document.getElementById('acp_board').style.display = 'none';
	}
	else {
		document.getElementById('submit').click();
	}
}

function abortWrite() {
	document.getElementById('ask_before_submit').style.display = 'none';
	document.getElementById('reset').click();
	document.getElementById('acp_board').style.display = 'block';
}

function writeData() {
	document.getElementById('submit').click();
}
