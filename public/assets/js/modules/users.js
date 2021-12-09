(function () {
	const passphrase = `Secure Login ${site_url}`;

	const userAuth = $('.user-auth');
	if (userAuth.length) {
		userAuth.on('click', async function (e) {
			e.preventDefault();

			// Verify is user have MetaMask installed
			if (!haveMetaMask()) {
				noMetaMask();
				return;
			}

			// Verify if active chain is the same as the one in the site
			const isValidChainId = await checkChainId();
			if (!isValidChainId) {
				if (!await changeNetwork()) {
					showAlert('Oops!', 'Invalid chain ID. Please change your network in MetaMask and try again.', 'error');
					return;
				}
			}

			const wallets = await getMetaMaskAccounts();
			if (wallets.length) {
				let secret = localStorage.getItem("Signed" + wallets[0]);
				if (!secret) {
					secret = await makeUserSign(wallets[0], passphrase);
					localStorage.setItem("Signed" + wallet, secret);
				}

				const wallet = await getFromSign(secret, passphrase);
				authWithMetaMask(wallet, secret);
			} else {
				showAlert('Oops!', 'Failed to get your wallet address. Please try again.', 'error');
			}
		});
	}

	const authWithMetaMask = async function (wallet, secret) {
		const activeWallet = await getActiveWallet();
		if (activeWallet !== wallet) {
			showAlert('Oops!', 'Invalid wallet address. Please reload the page and try again.', 'error');
			return;
		}

		axiosInstance.post('/login', { wallet, secret }).then((res) => {
			const { title, message, redirect, success } = res.data;
			if (success) {
				showAlert(title, message, 'success');
				window.location.href = redirect;
			}
		}).catch((err) => {
			const { title, message } = err.response.data;
			showAlert(title, message, 'error');
		});
	};
})();
