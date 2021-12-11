if (!isTestnet) {
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

const media_url = (image) => {
	return asset_url('media/' + image);
}

const storage_url = (file) => {
	return make_url('storage/' + file);
}

const bg				= new Audio(media_url('bgG.mp3'));
const buttonClick		= new Audio("https://s3-us-west-2.amazonaws.com/s.cdpn.io/217233/Buttonclick.mp3");
const featured			= new Audio("https://s3-us-west-2.amazonaws.com/s.cdpn.io/217233/featured.mp3");
const slideSlow			= new Audio("https://s3-us-west-2.amazonaws.com/s.cdpn.io/217233/slideSlow.mp3");
const countStats		= new Audio("https://s3-us-west-2.amazonaws.com/s.cdpn.io/217233/Countupstats.mp3");
const researchClick		= new Audio("https://s3-us-west-2.amazonaws.com/s.cdpn.io/217233/researchClick.mp3");
const researchInactive	= new Audio("https://s3-us-west-2.amazonaws.com/s.cdpn.io/217233/researchInactive.mp3");
const slideFast			= new Audio("https://s3-us-west-2.amazonaws.com/s.cdpn.io/217233/slideFast.mp3");
const featuredModal		= new Audio("https://s3-us-west-2.amazonaws.com/s.cdpn.io/217233/featuredModal.mp3");
const researchComplete	= new Audio("https://s3-us-west-2.amazonaws.com/s.cdpn.io/217233/researchComplete.mp3");

const globals = {
	paused:	true,
	audio:	true
};

const playSound = (sound) => {
	if (globals.audio) {
		sound.play();
	}
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

		dd.html('<i class="fas fa-cog fa-spin"></i> Loading...');

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

// Dark mode
const inDarkMode = () => {
	return localStorage.getItem('darkMode') == 'true';
}
const setDarkMode = (darkMode) => {
	$.LayoutThemeApp.changeMode(darkMode ? 'dark' : 'light');
	$.Topbar.changeColor(!darkMode ? 'dark' : 'light');
	localStorage.setItem('darkMode', darkMode);

	$(".change-theme").find('i').removeClass('noti-icon')
		.removeClass('fe-' + (darkMode ? 'moon' : 'sun'))
		.addClass('fe-' + (!darkMode ? 'moon' : 'sun'))
		.addClass('noti-icon');
};

const enableTooltip = () => {
	const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	tooltipTriggerList.map(function (tooltipTriggerEl) {
		return new bootstrap.Tooltip(tooltipTriggerEl, {
			html: true
		})
	})
}

// Transaction Checker
let runningCheck = false;
let transactionTimer;
const checkTransaction = (transcId) => {
	runningCheck = true;
	clearInterval(transactionTimer);

	transactionTimer = setInterval(async () => {
		const response = await axiosInstance.post('transactionCheck', { id: transcId });
		const { title, message, redirect, success, currency, idTransaction } = response.data;

		if (redirect) { setTimeout(() => { window.location.href = redirect; }, 3000); }

		if (success) {
			playSound(researchClick);
			showAlert(title, message, success ? 'success' : 'danger');

			clearInterval(transactionTimer);
			runningCheck = false;

			// Tenta atualizar na tela o saldo ddo usuário
			if (currency) { $('#myCurrency').html(currency); }

			// Começa a verificar a próxima transação pendente
			if (idTransaction) {
				checkTransaction(idTransaction);
			}
		}
	}, 5000);
}

$(document).ready(() => {
	// Enable Tooltips
	enableTooltip();

	// Check user pending transaction
	if (typeof userTransaction !== 'undefined' && userTransaction) {
		checkTransaction(userTransaction);
	}

	// Set selected theme
	setDarkMode(inDarkMode());

	// Change theme
	$(".change-theme").on('click', function() {
		setDarkMode(!inDarkMode())
	});

	$('[data-toggle="input-money"]').each(function(a, e) {
		$(e).maskMoney({
			thousands: '',
			allowZero: true,
			precision: 4
		});
	});

	// Instance of masks plugin
	$('[data-toggle="input-mask"]').each(function(a, e) {
        const t = $(e).data("maskFormat");
        const n = $(e).data("reverse");
        if (n != null) {
			$(e).mask(t, { reverse: n });
		} else {
			$(e).mask(t);
		}
    })
});
