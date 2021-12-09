(function () {
	const userBuildings = $('.user-buildings');
	if (userBuildings.length) {
		const buildingTemplate = ``;
		const loadBuildings = async () => {
			lockScreen(true);

			try {
				const response = await axiosInstance.get('/buildings');
				const { title, message, redirect, success, buildings, content } = response.data;

				lockScreen(false);

				buildings.forEach(building => {
					console.log(building);
				});
			} catch (err) {
				lockScreen(false);

				const { title, message } = err.response.data;
				showAlert(title, message, 'error');
			}
		};

		// First load
		loadBuildings();

		$('.mint', userBuildings).on('click', async function () {
			lockScreen(true);
			try {
				const response = await axiosInstance.post('/buildings/mint');

				lockScreen(false);
				if (response) {
					showAlert('Success', 'Your buildings have been minted', 'success');
					loadBuildings();
				}
			} catch (err) {
				lockScreen(false);

				const { title, message } = err.response.data;
				showAlert(title, message, 'error');
			}
		});

		$('.claim', userBuildings).on('click', async function () {
			lockScreen(true);

			const buildingId = $(this).data('id');
			try {
				const response = await axiosInstance.post('/buildings/claim', { id: buildingId });
				const { title, message, redirect, success, currency } = response.data;

				lockScreen(false);

				if (currency) {
					$('#myCurrency').html(currency);
				}

				loadBuildings();

				showAlert(title, message, success ? 'success' : 'danger');
				if (redirect) {
					setTimeout(() => {
						window.location.href = redirect;
					}, 3000);
				}
			} catch (err) {
				lockScreen(false);

				const { title, message } = err.response.data;
				showAlert(title, message, 'error');
			}
		});

		$('.upgrade', userBuildings).on('click', async function () {
			lockScreen(true);

			const buildingId = $(this).data('id');
			try {
				const response = await axiosInstance.post('/buildings/upgrade', { id: buildingId });
				const { title, message, redirect, success, currency } = response.data;

				lockScreen(false);

				if (currency) {
					$('#myCurrency').html(currency);
				}

				loadBuildings();

				showAlert(title, message, success ? 'success' : 'danger');
				if (redirect) {
					setTimeout(() => {
						window.location.href = redirect;
					}, 3000);
				}
			} catch (err) {
				lockScreen(false);

				const { title, message } = err.response.data;
				showAlert(title, message, 'error');
			}
		});

		$('.repair', userBuildings).on('click', async function () {
			lockScreen(true);

			const buildingId = $(this).data('id');
			try {
				const response = await axiosInstance.post('/buildings/repair', { id: buildingId });
				const { title, message, redirect, success, currency } = response.data;

				lockScreen(false);

				if (currency) {
					$('#myCurrency').html(currency);
				}

				loadBuildings();

				showAlert(title, message, success ? 'success' : 'danger');
				if (redirect) {
					setTimeout(() => {
						window.location.href = redirect;
					}, 3000);
				}
			} catch (err) {
				lockScreen(false);

				const { title, message } = err.response.data;
				showAlert(title, message, 'error');
			}
		});

		$('.sell', userBuildings).on('click', async function () {
			const buildingId = $(this).data('id');
			showAlert('Coming soon!', 'This feature is not available yet', 'info');
		});
	}
})();
