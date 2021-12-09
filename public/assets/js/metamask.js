const haveMetaMask = () => {
	return typeof web3 !== 'undefined';
};

const noMetaMask = () => {
	Swal.fire({
		icon: 'warning',
		title: 'Install MetaMask',
		text: 'No MetaMask detected, please install MetaMask!',
		confirmButtonText: "Yes, install it!"
	}).then((result) => {
		if (result.value) {
			window.open("https://chrome.google.com/webstore/detail/metamask/nkbihfbeogaeaoehlefnkodbefgpgknn", '_blank').focus();
		}
	});
};

const addCustomToken = async () => {
	return await ethereum.request({
		method: 'wallet_watchAsset',
		params: tokenParams
	});
}

const getMetaMaskAccounts = async () => {
	try {
		return await ethereum.request({
			method: 'eth_requestAccounts'
		});
	} catch (err) {
		return false;
	}
};

const checkChainId = async () => {
	try {
		const chainId = await ethereum.request({
			method: 'eth_chainId'
		});

		return chainId == redOficial;
	} catch (err) {
		return false;
	}
};

const getActiveWallet = async () => {
	try {
		const activeWallet = await ethereum.request({
			method: 'eth_accounts'
		});
		if (activeWallet.length) {
			return activeWallet[0];
		} else {
			return false;
		}
	} catch (err) {
		return false;
	}
};


const changeNetwork = async () => {
	try {
		return await ethereum.request({
			method: 'wallet_switchEthereumChain',
			params: [{
				chainId: redOficial
			}]
		}).then(() => {
			return true;
		}).catch(async (err) => {
			if (err.code == 4001) {
				showAlert("Alert", "You have rejected the network change", 'error');
			} else if (err.code == 4902) {
				showAlert("Alert", "It seems that you do not have this network added to your MetaMask, I will give you a hand", 'warning');
				if (!isTestnet) {
					if (!await addMainnetNetwork()) {
						return false;
					}
				} else {
					if (!await addTestnetNetwork()) {
						return false;
					}
				}

				return await changeNetwork();
			}
			return false;
		});
	} catch (err) {
		return false;
	}
};

const addMainnetNetwork = async () => {
	return await ethereum.request({
		method: 'wallet_addEthereumChain',
		params: [{
			chainId: '0x38',
			chainName: 'BSC - Mainnet',
			nativeCurrency: {
				name: "BNB",
				decimals: 18,
				symbol: "BNB"
			},
			rpcUrls: [ 'https://bsc-dataseed.binance.org/' ],
			blockExplorerUrls: [ "https://bscscan.com" ]
		}],
	}).then(() => {
		return true;
	}).catch(() => {
		showAlert("Alert", "There was a problem adding the network ", 'error');
		return false;
	});
};

const addTestnetNetwork = async () => {
	return await ethereum.request({
		method: 'wallet_addEthereumChain',
		params: [{
			chainId: '0x61',
			chainName: 'BSC - Testnet',
			nativeCurrency: {
				name: "BNB",
				decimals: 18,
				symbol: "BNB"
			},
			rpcUrls: [ 'https://data-seed-prebsc-1-s1.binance.org:8545/' ],
			blockExplorerUrls: [ "https://testnet.bscscan.com" ]
		}],
	}).then(() => {
		return true;
	}).catch(() => {
		showAlert("Alert", "There was a problem adding the network ", 'error');
		return false;
	});
};

const makeUserSign = async (string, passphrase) => {
	try {
		return await ethereum.request({
			method: 'personal_sign',
			domain: domainName,
			params: [ string, passphrase ],
		}).then((sign) => {
			return sign;
		}).catch(() => {
			showAlert("Alert", "You have rejected the signature", 'error');
			return false;
		});
	} catch (err) {
		showAlert("Alert", "You have rejected the signature", 'error');
		return false;
	}
}

const getFromSign = async (secret, passphrase) => {
	try {
		return await ethereum.request({
			method: 'personal_ecRecover',
			params: [ passphrase, secret ],
		}).catch((err) => {
			showAlert("Alert", "Your signature is invalid", 'error');
			return false;
		})
	} catch (err) {
		showAlert("Alert", "Fail to get your wallet address", 'error');
		return false;
	}
}
