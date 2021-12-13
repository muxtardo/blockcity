export default function() {
    const { lockScreen, showAlert } = require('../utils/global');
    const { haveMetaMask, noMetaMask, checkChainId, getMetaMaskAccounts, makeUserSign, getFromSign, changeNetwork, getActiveWallet } = require('../utils/metamask');
    const passphrase = `Secure Login ${site_url}`;
    const authWithMetaMask = async function (wallet, secret) {
        const activeWallet = await getActiveWallet();
        if (activeWallet.toLowerCase() !== wallet) {
            lockScreen(false);
            showAlert(__('Oops!'), __('Invalid wallet address. Please reload the page and try again.'), 'error');
            return;
        }

        axios.post('auth', { wallet, secret }).then((res) => {
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

    $('.user-auth').on('click', async function (e) {
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
                showAlert(__('Oops!'), __('Invalid chain ID. Please change your network in MetaMask and try again.'), 'error');
                return;
            }
        }

        const wallets = await getMetaMaskAccounts();
        if (wallets.length) {
            let secret = localStorage.getItem("Signed" + wallets[0].toLowerCase());
            if (!secret) {
                secret = await makeUserSign(wallets[0].toLowerCase(), passphrase);
                localStorage.setItem("Signed" + wallets[0].toLowerCase(), secret);
            }

            const wallet = await getFromSign(secret, passphrase);
            authWithMetaMask(wallet.toLowerCase(), secret);
        } else {
            lockScreen(false);
            showAlert(__('Oops!'), __('Failed to get your wallet address. Please try again.'), 'error');
        }
    });
}
