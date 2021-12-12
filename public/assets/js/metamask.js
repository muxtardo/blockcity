let web3x		= new Web3('https://bsc-dataseed.binance.org/');
let redOficial	= "0x38";
let redChainID	= 56;

if (isTestnet) {
	web3x		= new Web3('https://data-seed-prebsc-1-s1.binance.org:8545/');
	redChainID	= 97;
	redOficial	= "0x61";
}

let providerMeta	= false
	signer			= false;
try {
	providerMeta	= new ethers.providers.Web3Provider(window.ethereum);
	signer			= providerMeta.getSigner();
} catch (err) {
	noMetaMask();
}

function haveMetaMask() {
	return typeof web3 !== 'undefined' && window.ethereum;
};

function noMetaMask() {
	lockScreen(false);

	Swal.fire({
		icon: 'warning',
		title: __('Install MetaMask'),
		text: __('No MetaMask detected, please install MetaMask!'),
		confirmButtonText: __("Yes, :action it!", { action: 'install' }),
		showCancelButton: true,
		caancelButtonText: __('Cancel')
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
				showAlert(__("Alert"), __("You have rejected the network change"), 'error');
			} else if (err.code == 4902) {
				showAlert(__("Alert"), __("It seems that you do not have this network added to your MetaMask, I will give you a hand"), 'warning');
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
		showAlert(__("Alert"), __("There was a problem adding the network"), 'error');
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
		showAlert(__("Alert"), __("There was a problem adding the network"), 'error');
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
			showAlert(__("Alert"), __("You have rejected the signature"), 'error');
			return false;
		});
	} catch (err) {
		showAlert(__("Alert"), __("You have rejected the signature"), 'error');
		return false;
	}
}

const getFromSign = async (secret, passphrase) => {
	try {
		return await ethereum.request({
			method: 'personal_ecRecover',
			params: [ passphrase, secret ],
		}).catch((err) => {
			showAlert(__("Alert"), __("Your signature is invalid"), 'error');
			return false;
		})
	} catch (err) {
		showAlert(__("Alert"), __("Fail to get your wallet address"), 'error');
		return false;
	}
}

const getTransactionReceipt = async (txHash) => {
	if (!providerMeta) {
		return false;
	}

	if (txHash.length != 66) {
		showAlert(__('Error'), __('Incorrect hash! Check it and try again.'), 'error');
		return false;
	}

	let txReceipt = await providerMeta.getTransactionReceipt(txHash);
	if (txReceipt) {
		return txReceipt;
	}

	return false;
};

const getTokenBalance = async (wallet) => {
	if (!providerMeta) {
		return false;
	}

	const contractAbi	= await $.getJSON(storage_url('contractAbi.json'));
	const BNBContract	= new ethers.Contract(gameContract, contractAbi, signer);
	const resBalance	= await BNBContract.balanceOf(wallet);
	if (resBalance) {
		return Web3.utils.fromWei(resBalance._hex, 'wei');
	}

	return 0;
};

const loadTokenBalance = async () => {
	const myTokens = $("#myTokens");
	if (myTokens.length) {
		let balance = await getTokenBalance(userWallet);
		balance = parseInt(balance);
		myTokens.html(parseFloat(balance / 10000).toFixed(4));
	}
};

const transferToken = async (wallet, amount) => {
	if (!providerMeta) {
		return false;
	}

	const contractAbi	= await $.getJSON(storage_url('contractAbi.json'));
	const BNBContract	= new ethers.Contract(gameContract, contractAbi, signer);
	const txHash		= await BNBContract.transfer(wallet, amount, {
		'gasLimit': 150000,
		'gasPrice': ethers.utils.parseUnits('10.0', 'gwei')
	});

	if (txHash) {
		return txHash;
	}

	return false;
}

const transferBNB = async (sender, receiver, amount) => {
	if (!Web3.utils.isAddress(sender)) {
		showAlert(__('Error'), __('Incorrect sender wallet address! Check it and try again.'), 'error');
		return false;
	}

	if (!Web3.utils.isAddress(receiver)) {
		showAlert(__('Error'), __('Incorrect receiver wallet address! Check it and try again.'), 'error');
		return false;
	}

	amount = parseFloat(amount);
	if (amount <= 0) {
		showAlert(__('Error'), __('Incorrect amount! Check it and try again.'), 'error');
		return false;
	}

	return await ethereum.request({
		method: 'eth_sendTransaction',
		params: [{
			from:		sender,
			value:		Web3.utils.toHex(Web3.utils.toWei(amount + "")),
			to:			receiver,
			chainId:	redOficial
		}]
	}).catch((err) => {
		if (err.code == 4001) {
			showAlert(__("Warning"), __("Transaction canceled"), 'warning');
		} else {
			showAlert(__("Warning"), __("Transaction error"), 'warning');
		}

		return false;
	});
};
