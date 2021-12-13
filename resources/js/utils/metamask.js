
const { WEB3X, CHAIN_ID, CHAIN_NAME, BSCSCAN } = process.env;
const { lockScreen, showAlert, storage_url } = require('../utils/global');

let providerMeta	= false;
let	signer			= false;

const haveMetaMask = () => {
	return typeof web3 !== 'undefined' && window.ethereum;
};

const noMetaMask = () => {
	lockScreen(false);

	Swal.fire({
		icon: 'warning',
		title: __('Install MetaMask'),
		text: __('No MetaMask detected, please install MetaMask!'),
		confirmButtonText: __("Yes, :action it!", { action: __("install") }),
		showCancelButton: true,
		cancelButtonText: __('Cancel')
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

		return chainId == CHAIN_ID;
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
				chainId: CHAIN_ID
			}]
		}).then(() => {
			return true;
		}).catch(async (err) => {
			if (err.code == 4001) {
				showAlert(__("Alert"), __("You have rejected the network change"), 'error');
			} else if (err.code == 4902) {
				showAlert(__("Alert"), __("It seems that you do not have this network added to your MetaMask, I will give you a hand"), 'warning');
				await addNetwork();
				return await changeNetwork();
			}
			return false;
		});
	} catch (err) {
		return false;
	}
};

const addNetwork = async () => {
	return await ethereum.request({
		method: 'wallet_addEthereumChain',
		params: [{
			chainId: CHAIN_ID,
			chainName: CHAIN_NAME,
			nativeCurrency: {
				name: "BNB",
				decimals: 0x12,
				symbol: "BNB"
			},
			rpcUrls: [ WEB3X ],
			blockExplorerUrls: [ BSCSCAN ]
		}],
	}).then(() => {
		return true;
	}).catch(() => {
		showAlert(__("Alert"), __("There was a problem adding the network"), 'error');
		return false;
	});
}

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
	return txHash;
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
			chainId:	CHAIN_ID
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

const loadTokenBalance = async () => {
	const myTokens = $("#myTokens");
	let balance = await getTokenBalance(userWallet);
	balance = parseInt(balance);
	myTokens.html(parseFloat(balance / 10000).toFixed(4));
};

try {
	providerMeta	= new ethers.providers.Web3Provider(window.ethereum);
	signer			= providerMeta.getSigner();
} catch (err) {
	noMetaMask();
}

export {
	addNetwork,
	makeUserSign,
	getFromSign,
	getTransactionReceipt,
	getTokenBalance,
	transferToken,
	transferBNB,
	haveMetaMask,
	noMetaMask,
	addCustomToken,
	getMetaMaskAccounts,
	checkChainId,
	getActiveWallet,
	changeNetwork,
	loadTokenBalance
};
