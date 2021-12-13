export default async function () {
	const { BSCSCAN, WALLET_PAGOS, CONTRACT_ADDRESS } = process.env;
	const { lockScreen, showAlert, checkTransaction } = require('../utils/global');
	const { getTransactionReceipt, getTokenBalance, transferToken, loadTokenBalance, validateHash, getMetaMaskAccounts } = require('../utils/metamask');

	const exchange = $('.exchange');
	let pendingTransaction = false;

	const exchangeAppData = {
		data() {
			return {
				counter: 0,
				transactions: Vue.reactive({ value: {}}),
				current_page: 1,
				number_per_page: 10,
				total_transactions: 0,
				last_load_page: 1,
				linkBSC: BSCSCAN
			}
		},
		methods: {
			async load_transactions(page = 1) {
				lockScreen(true);

				const response = await axios.get('exchange/transactions', {
					params: { page }
				}).then(res => res.data);
				const { transactions, stats: {
					total: countTransactions
				}} = response;
				if (page == 1) { this.total_transactions = countTransactions; }

				lockScreen(false);
				const transactionsObject = transactions.reduce((acc, transaction) => ({...acc, [transaction.id]: transaction }), {});
				return transactionsObject;
			},
			async reset_transactions() {
				this.last_load_page = 0;
				this.current_page = 1;
				this.nextPage(this.current_page);
			},
			pageLoaded(number) {
				const pages_loaded = Math.ceil(Object.values(this.transactions.value).length / this.number_per_page);

				return this.last_load_page < number;
			},
			async nextPage(next) {
				if (this.pageLoaded(next)) {
					this.last_load_page = next;
					lockScreen(true);

					const transactions = await this.load_transactions(next);
					this.transactions.value = Object.assign(this.transactions.value, transactions);

					lockScreen(false);
				}

				this.$nextTick(() => {
					this.current_page = next;
				});
			}
		},
		async mounted() {
			this.transactions.value = Object.assign(this.transactions.value, await this.load_transactions());
			this.nextPage(1);
		},
		computed: {
			transactionsFiltered() {
				const sorted	= Object.values(this.transactions.value).sort((a, b) => b.id - a.id);
				const data		= sorted.slice((this.current_page - 1) * this.number_per_page, (this.current_page) * this.number_per_page);
				return data;
			},
			totalPages() {
				return Math.ceil(this.total_transactions / this.number_per_page);
			},
			loaded_all_transactions() {
				return Object.values(this.transactions.value).length >= this.total_transactions;
			},
		}
	}

	window.exchangeApp = Vue.createApp(exchangeAppData).mount('.user-transactions');

	loadTokenBalance();

	const formConsult = $("#form-consult", exchange);
	if (formConsult.length) {
		formConsult.on('submit', async function (e) {
			lockScreen(true);

			const transHash	= $(".hash", formConsult).val();
			if (!validateHash(transHash)) {
				showAlert(__('Error'), __('Invalid transaction hash'), 'error');
				lockScreen(false);
				return false;
			}

			const txReceipt	= await getTransactionReceipt(transHash);
			if (!txReceipt) {
				lockScreen(false);
				showAlert(__('Error'), __('Transaction not found! Check it and try again.'), 'error');
				return false;
			}

			if (txReceipt.status == 0) {
				lockScreen(false);
				showAlert(__('Error'), __('Transaction status failed'), 'error');
				return false;
			}

			if (txReceipt.to != CONTRACT_ADDRESS) {
				lockScreen(false);
				showAlert(__('Error'), __('This is not a transaction to the game contract'), 'error');
				return false;
			}

			let receiver = txReceipt.logs[0].topics[2];
			if (receiver == null) {
				lockScreen(false);
				showAlert(__('Error'), __('Transaction logs error'), 'error');
				return false;
			}
			receiver = receiver.toLowerCase();

			let sender = txReceipt.from.toLowerCase();
			if (sender != userWallet) {
				lockScreen(false);
				showAlert(__('Error'), __('This hash does not belong to this account'), 'error');
				return false;
			}

			let walletPag = WALLET_PAGOS.toLowerCase().substr(2);
			if (!receiver.includes(walletPag)) {
				lockScreen(false);
				showAlert(__('Error'), __('Transaction destination wrong'), 'error');
				return false;
			}

			try {
				const response = await axios.post('exchange/consult', { hash: txReceipt.transactionHash });
				const { title, message, redirect, success, currency } = response.data;

				lockScreen(false);
				if (currency) {
					$('#myCurrency').html(currency);
					exchangeApp.reset_transactions();
				}

				showAlert(title, message, success ? 'success' : 'danger');
				if (redirect) {
					setTimeout(() => { window.location.href = redirect; }, 3000);
				}
			} catch (err) {
				lockScreen(false);

				const { title, message } = err.response.data;
				showAlert(title, message, 'error');
			}
			return false;
		});
	};

	const formDeposit = $("#form-deposit", exchange);
	if (formDeposit.length) {
		formDeposit.on('submit', async function (e) {
			lockScreen(true);

			const wallets = await getMetaMaskAccounts();
			if (wallets.length == 0) {
				lockScreen(false);
				showAlert(__('Error'), __('You must connect your wallet'), 'error');
				return false;
			}

			const wallet = wallets[0].toLowerCase();
			if (wallet != userWallet) {
				lockScreen(false);
				showAlert(__('Error'), __('This wallet is not your account'), 'error');
				return false;
			}

			let amount = parseFloat($(".amount", formDeposit).val()).toFixed(4);
			if (amount <= 0) {
				lockScreen(false);
				showAlert(__('Error'), __('Amount must be greater than 0'), 'error');
				return false;
			}
			amount = parseFloat(amount * 10000).toFixed(0);
			amount = parseInt(amount);

			let balance = await getTokenBalance(userWallet);
			balance = parseInt(balance);
			if (balance < amount) {
				lockScreen(false);
				let diffTokens	= parseFloat((amount - balance) / 10000).toFixed(4);
				showAlert(__("Alert"), __(`Insuficient Balance, you need +:amount more Tokens`, {amount: diffTokens}), 'info');
				return false;
			}

			try {
				pendingTransaction = true;
				const txHash = await transferToken(WALLET_PAGOS, amount);
				if (!txHash) {
					lockScreen(false);

					showAlert(__('Error'), __('Error sending tokens'), 'error');
					return false;
				}
				loadTokenBalance();

				const response = await axios.post('exchange/deposit', {
					amount:	amount / 10000,
					hash:	txHash.hash
				});
				const { title, message, redirect, success, currency, transactionId } = response.data;

				// Update interface with new user balance
				exchangeApp.reset_transactions();
				if (currency) {
					$('#myCurrency').html(currency);

				}
				if (transactionId) { checkTransaction(transactionId); }

				showAlert(title, message, success ? 'success' : 'danger');
				if (redirect) {
					setTimeout(() => { window.location.href = redirect; }, 3000);
				}
			} catch (err) {
				if (err.code == -32603) {
					showAlert(__("Error"), __('Transaction underpriced!'), 'error');
				} else if (err.code == 4001) {
					showAlert(__("Error"), __("Transaction canceled"), 'error');
				} else {
					showAlert(__("Error"), __("Transaction error"), 'error');
				}

				return false;
			} finally {
				pendingTransaction = false;
				lockScreen(false);
			}
		});
	}

	const formWithdrawal = $("#form-withdrawal", exchange);
	if (formWithdrawal.length) {
		formWithdrawal.on('submit', async function (e) {
			lockScreen(true);

			let amount	= parseFloat($(".amount", formWithdrawal).val());
			if (amount <= 0) {
				lockScreen(false);

				showAlert(__('Error'), __('Amount must be greater than 0'), 'error');
				return false;
			}

			const response = await axios.post('exchange/withdrawal', { amount });
			const { title, message, redirect, success, currency, transactionId } = response.data;

			// Update interface with new user balance
			if (currency) {
				loadTokenBalance();
				$('#myCurrency').html(currency);
				exchangeApp.reset_transactions();
			}
			if (transactionId) { checkTransaction(transactionId); }

			showAlert(title, message, success ? 'success' : 'danger');
			if (redirect) { setTimeout(() => { window.location.href = redirect; }, 3000); }
		});
	}

	$(window).on("beforeunload", function() {
		if (pendingTransaction) {
			return __('You have a pending transaction, are you sure you want to leave?');
		}
	});
};
