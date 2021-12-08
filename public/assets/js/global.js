let web3x		= new Web3('https://bsc-dataseed.binance.org/');
let redOficial	= "0x38";
let redChainID	= 56;

if (isTestnet) {
	web3x			= new Web3('https://data-seed-prebsc-1-s1.binance.org:8545/');
	redChainID		= 97;
	redOficial		= "0x61";
} else {
	const channel = new BroadcastChannel(domainName);
	channel.postMessage({
		action: "ping"
	});

	channel.addEventListener("message", e => {
		if (e.data.action == "ping") {
			todobien		= false;
			window.location	= "/duplicated";
		}
	});
}

if (typeof window.ethereum !== 'undefined') {
	$(".loginmetamask").click(function() {
		ethereum.request({
			method: 'eth_requestAccounts'
		}).then(function(wallets) {
			loginWithMetamask(wallets[0]);
		}).catch((err) => {
			console.log(err);
		});
	});

	if (window.ethereum) {
		providerMeta = new ethers.providers.Web3Provider(window.ethereum);

		let noReload = false;
		ethereum.on('accountsChanged', (wallets) => { // when changing account
			loginWithMetamask(wallets[0]);
		});

		ethereum.on('chainChanged', (chainId) => { // when changing network
			if (noReload) {
				return false;
			}

			noReload	= true;
			window.location.reload();
		});
	} else {
		noMetaMask();
	}
} else {
	noMetaMask();
}

function noMetaMask() {
	$(".loginmetamask").click(function() {
		Swal.fire({
			icon: 'warning',
			title: 'Install MetaMask',
			text: 'No MetaMask detected, please install MetaMask!',
			confirmButtonText: "Yes, install it!"
		}).then(function (result) {
			if (result.value) {
				window.open("https://chrome.google.com/webstore/detail/metamask/nkbihfbeogaeaoehlefnkodbefgpgknn", '_blank').focus();
			}
		});
	});
}

function reloadNetwork() {
	try {
		showAlert("Alert", "You re on the wrong network, a warning has been sent in your metamask to change it ", 'warning');

		async function asyncWalletChange() {
			await ethereum.request({
				method: 'wallet_switchEthereumChain',
				params: [{
					chainId: redOficial
				}]
			}).catch((err) => {
				if (err.code == 4001) {
					showAlert("Alert", "You have rejected the network change", 'error');
				} else if (err.code == 4902) {
					showAlert("Alert", "It seems that you do not have this network added to your MetaMask, I will give you a hand", 'warning');
					async function asyncChangeChain() {
						if (!isTestnet) {
							await ethereum.request({
								method: 'wallet_addEthereumChain',
								params: [{
									chainId: redOficial,
									chainName: 'BSC - Mainnet',
									nativeCurrency: {
										name: "BNB",
										decimals: 18,
										symbol: "BNB"
									},
									rpcUrls: [ 'https://bsc-dataseed.binance.org/' ],
									blockExplorerUrls: [ "https://bscscan.com" ]
								}],
							}).catch((err) => {
								showAlert("Alert", "There was a problem adding the network ", 'error');
							});
						} else {
							await ethereum.request({
								method: 'wallet_addEthereumChain',
								params: [{
									chainId: redOficial,
									chainName: 'BSC - Testnet',
									nativeCurrency: {
										name: "BNB",
										decimals: 18,
										symbol: "BNB"
									},
									rpcUrls: [ 'https://data-seed-prebsc-1-s1.binance.org:8545/' ],
									blockExplorerUrls: [ "https://testnet.bscscan.com" ]
								}],
							}).catch((err) => {
								showAlert("Alert", "There was a problem adding the network ", 'error');
							});
						}
					}
					asyncChangeChain();
				}
			});
		}
		asyncWalletChange();
	} catch (e) {
		console.error(e);
	}
}

async function addCustomToken() {
	await ethereum.request({
		method: 'wallet_watchAsset',
		params: tokenParams
	});
}

async function loginWithMetamask(wallet) {
	const chainId = await ethereum.request({
		method: 'eth_chainId'
	});

	if (chainId != redOficial) {
		reloadNetwork();
		return false;
	}

	const accounts = await ethereum.request({
		method: 'eth_accounts'
	});
	const account = accounts[0];

	if (account == undefined || account != wallet) {
		return false;
	}

	let passphrase	= `Secure Login ${site_url}`;
	let secretKey	= localStorage.getItem("Signed" + wallet);
	if (secretKey == undefined) {
		await ethereum.request({
			method: 'personal_sign',
			domain: domainName,
			params: [ wallet, passphrase ],
		}).then((res) => {
			console.log(res);
			secretKey = res;
			localStorage.setItem("Signed" + wallet, res);
		}).catch((err) => {
			console.error(err);

			showAlert("Alert", "You have rejected the signature", 'error');
		});
	} else {
		await ethereum.request({
			method: 'personal_ecRecover',
			params: [ passphrase, secretKey ],
		}).then((res) => {
			$.ajax({
				url: site_url + "/auth",
				data: {
					wallet: res,
					secret: secretKey,
				},
				type: "POST",
				dataType: "json",
				success: function(result) {
					if (!result.success) {
						showAlert("Alert", result.message, 'error');
					} else {
						showAlert("Alert", "You have successfully logged in", 'success');
					}
				}
			});

			showAlert("Success", "Your signature is correct", 'success');
		}).catch((err) => { // signature not correct
			console.error(err);

			localStorage.removeItem("Signed" + wallet);
			showAlert("Alert", "Your signature is not correct, please try again", 'error');
		});
	}
}

async function handleAccountsChanged(acc, refresh) {
	let actualAccount = accountMeta;
	if (actualAccount == null) {
		actualAccount = "";
	}

	const chainId = await ethereum.request({
		method: 'eth_chainId'
	});
	if (chainId == redOficial) {
		var accounts = await ethereum.request({
			method: 'eth_accounts'
		});

		var account = accounts[0];
		if (account == undefined) {
			account = "";
		}

		if (account != acc) {
			return false;
		}

		if (account != "") {
			if (account.length > 10) {
				accountMeta = account.toLowerCase();
				if (refresh) {
					window.location.reload();
					return false;
				}

				let signatu	= `Secure Login ${site_url}`;
				let signature	= localStorage.getItem("Signed" + accountMeta);
				if (signature == undefined) { // is the key when signing
					await ethereum.request({
						method: 'personal_sign',
						domain: domainName,
						params: [ accountMeta, signatu ],
					}).then((res) => {
						secretKey	= res;
						localStorage.setItem("Signed" + accountMeta, res);

						showAlert("Alert", "You have been logged in", 'success');
					}).catch((err) => { // does not want to sign
						console.error(err);

						showAlert("Alert", "Failed to login, please try again", 'error');
					});
				} else {
					await ethereum.request({
						method: 'personal_ecRecover',
						params: [ signatu, signature ],
					}).then((res) => { // correct signature
						loadAllAccountData(accountMeta, false, 0);
					}).catch((err) => { // signature not correct
						localStorage.removeItem(versCache + "signed" + accountMeta);
						deleteAccountData();
					});
				}
			}
		} else {
			deleteAccountData();
			ethereum.request({
				method: 'eth_requestAccounts'
			}).then(function(acc) {
				handleAccountsChanged(acc, false);
			}).catch((err) => {

			});
		}
	} else {
		reloadNetwork();
	}
}


function showAlert(title, text, type) {
	Swal.fire({
		icon: type || 'info',
		title: title || '',
		text: text || '',
		confirmButtonText: "Ok"
	});
}
