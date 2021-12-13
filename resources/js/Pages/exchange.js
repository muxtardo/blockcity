const loadTokenBalance = async () => {
	const myTokens = $("#myTokens");
	let balance = await getTokenBalance(userWallet);
	balance = parseInt(balance);
	myTokens.html(parseFloat(balance / 10000).toFixed(4));
};