let web3x		= new Web3('https://bsc-dataseed.binance.org/');
let redOficial	= "0x38";
let redChainID	= 56;

if (isTestnet) {
	web3x			= new Web3('https://data-seed-prebsc-1-s1.binance.org:8545/');
	redChainID		= 97;
	redOficial		= "0x61";
} else {
	const channel = new BroadcastChannel(domainName);
	channel.postMessage({ action: "ping" });
	channel.addEventListener("message", e => {
		if (e.data.action == "ping") {
			window.location.href	= "/duplicated";
		}
	});
}

const make_url = (url) => {
	return site_url + '/' + url;
}

const asset_url = (asset) => {
	return make_url('assets/' + asset);
}

const image_url = (image) => {
	return asset_url('images/' + image);
}

const storage_url = (file) => {
	return make_url('storage/' + file);
}

const showAlert = (title, text, type) => {
	return Swal.fire({
		icon: type || 'info',
		title: title || 'Error',
		text: text || 'Something Wrong',
		confirmButtonText: "Ok"
	});
}

const lockScreen = (show) => {
	if (show) {
		lockActions = true;

		var d	= $(document.createElement('DIV')).addClass('screen-lock');
		var dd	= $(document.createElement('DIV')).addClass('screen-lock-text');

		dd.html('Waiting...');

		$(document.body).append(d, dd).css('overflow', 'hidden');

		if (!window.has_screen_lock_callback) {
			window.has_screen_lock_callback	= true;

			$(window).on('resize', () => {
				$('.screen-lock')
					.css('width', $(window).width())
					.css('height', $(window).height());
			});
		}
	} else {
		lockActions = false;

		$(document.body).css('overflow', 'auto');
		$('.screen-lock, .screen-lock-text').remove();
	}
};
