(function () {
	const passphrase = `Secure Login ${site_url}`;

	const userAuth = $('.user-auth');
	if (userAuth.length) {
		userAuth.on('click', async function (e) {
			e.preventDefault();

			lockScreen(true);

			// Verify is user have MetaMask installed
			if (!haveMetaMask()) {
				noMetaMask();
				return;
			}

			// Verify if active chain is the same as the one in the site
			const isValidChainId = await checkChainId();
			if (!isValidChainId) {
				if (!await changeNetwork()) {
					lockScreen(false);
					showAlert(__('Alert'), __('Invalid chain ID. Please change your network in MetaMask and try again.'), 'error');
					return;
				}
			}

			const wallets = await getMetaMaskAccounts();
			if (wallets.length) {
				let secret = localStorage.getItem("Signed" + wallets[0]);
				if (!secret) {
					secret = await makeUserSign(wallets[0], passphrase);
					localStorage.setItem("Signed" + wallets[0], secret);
				}

				const wallet = await getFromSign(secret, passphrase);
				authWithMetaMask(wallet, secret);
			} else {
				lockScreen(false);
				showAlert(__('Alert'), __('Failed to get your wallet address. Please try again.'), 'error');
			}
		});
	}

	const authWithMetaMask = async function (wallet, secret) {
		const activeWallet = await getActiveWallet();
		if (activeWallet !== wallet) {
			lockScreen(false);
			showAlert(__('Alert'), __('Invalid wallet address. Please reload the page and try again.'), 'error');
			return;
		}

		axiosInstance.post('auth', { wallet, secret }).then((res) => {
			const { redirect, success, title, message } = res.data;

			// showAlert(title, message, success ? 'success' : 'error');
			if (redirect) {
				setTimeout(() => { window.location.href = redirect; }, 1000);
			}
		}).catch((err) => {
			lockScreen(false);
			const { title, message } = err.response.data;
			showAlert(title, message, 'error');
		});
	};
})();
