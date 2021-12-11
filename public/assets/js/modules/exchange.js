(async function () {
	const exchange = $('.exchange');
	if (exchange.length) {
		loadTokenBalance();

		const formConsult = $("#form-consult");
		if (formConsult.length) {
			formConsult.on('submit', async function (e) {
				lockScreen(true);

				const transHash	= $(".hash", formConsult).val();
				const txReceipt	= await getTransactionReceipt(transHash);
				if (!txReceipt) {
					lockScreen(false);
					showAlert('Error', 'Transaction not found! Check it and try again.', 'error');
					return false;
				}

				if (txReceipt.status == 0) {
					lockScreen(false);
					showAlert('Error', 'Transaction status failed', 'error');
					return false;
				}

				if (txReceipt.to != gameContract) {
					lockScreen(false);
					showAlert('Error', 'This is not a transaction to the game contract', 'error');
					return false;
				}

				let receiver = txReceipt.logs[0].topics[2];
				if (receiver == null) {
					lockScreen(false);
					showAlert('Error', 'Transaction logs error', 'error');
					return false;
				}
				receiver = receiver.toLowerCase();

				let sender = txReceipt.from.toLowerCase();
				if (sender != userWallet) {
					lockScreen(false);
					showAlert('Error', 'This hash does not belong to this account', 'error');
					return false;
				}

				let walletPag = walletPagos.toLowerCase().substr(2);
				if (!receiver.includes(walletPag)) {
					lockScreen(false);
					showAlert('Error', 'Transaction destination wrong', 'error');
					return false;
				}

				try {
					const response = await axiosInstance.post('exchange/consult', { hash: txReceipt.transactionHash });
					const { title, message, redirect, success, currency } = response.data;

					lockScreen(false);
					if (currency) { $('#myCurrency').html(currency); }

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

		const formDeposit = $("#form-deposit");
		if (formDeposit.length) {
			formDeposit.on('submit', async function (e) {
				lockScreen(true);

				let amount = parseFloat($(".amount", formDeposit).val()).toFixed(4);
				if (amount <= 0) {
					lockScreen(false);

					showAlert('Error', 'Amount must be greater than 0');
					return false;
				}
				amount = parseFloat(amount * 10000).toFixed(0);
				amount = parseInt(amount);

				let balance = await getTokenBalance(userWallet);
				balance = parseInt(balance);
				if (balance < amount) {
					lockScreen(false);

					let diffTokens	= parseFloat((amount - balance) / 10000).toFixed(4);
					showAlert("Alert", `Insuficient Balance, you need +${diffTokens} more Tokens`, 'info');
					return false;
				}

				try {
					const txHash = await transferToken(walletPagos, amount);
					if (!txHash) {
						lockScreen(false);

						showAlert('Error', 'Error sending tokens', 'error');
						return false;
					}
					loadTokenBalance();

					const response = await axiosInstance.post('exchange/deposit', {
						amount:	amount / 10000,
						hash:	txHash.hash
					});
					const { title, message, redirect, success, currency, transactionId } = response.data;

					// Update interface with new user balance
					if (currency) { $('#myCurrency').html(currency); }
					if (transactionId) { checkTransaction(transactionId); }

					showAlert(title, message, success ? 'success' : 'danger');
					if (redirect) {
						setTimeout(() => { window.location.href = redirect; }, 3000);
					}
				} catch (err) {
					if (err.code == -32603) {
						showAlert("Error", 'Transaction underpriced!', 'error');
					} else if (err.code == 4001) {
						showAlert("Error", "Transaction canceled", 'error');
					} else {
						showAlert("Error", "Transaction error", 'error');
					}

					return false;
				} finally {
					lockScreen(false);
				}
			});
		}

		const formWithdrawal = $("#form-withdrawal");
		if (formWithdrawal.length) {
			formWithdrawal.on('submit', async function (e) {
				lockScreen(true);

				let amount	= parseFloat($(".amount", formWithdrawal).val());
				if (amount <= 0) {
					lockScreen(false);

					showAlert('Error', 'Amount must be greater than 0');
					return false;
				}

				const response = await axiosInstance.post('exchange/withdrawal', { amount });
				const { title, message, redirect, success, currency, transactionId } = response.data;

				// Update interface with new user balance
				if (currency) {
					loadTokenBalance();
					$('#myCurrency').html(currency);
				}
				if (transactionId) { checkTransaction(transactionId); }

				showAlert(title, message, success ? 'success' : 'danger');
				if (redirect) { setTimeout(() => { window.location.href = redirect; }, 3000); }
			});
		}
	}
})();
