(function () {
	const userBuildings = $('.user-buildings');
	if (userBuildings.length) {
		const buildingTemplate = ``;
		const loadBuildings = async () => {
			try {
				const response = await axiosInstance.get('/buildings');
				const { title, message, redirect, success, buildings } = response.data;
				buildings.forEach(building => {
					console.log(building);
				});
			} catch (e) {
				console.log(e);
				showAlert(e.response.data.title, e.response.data.message, 'error');
			}
		};

		$('.claim', userBuildings).on('click', async () => {
			lockScreen(true);

			const buildingId = $(this).data('id');
			try {
				const response = await axiosInstance.post('/building/upgrade', { id: buildingId });
				const { title, message, redirect, success, currency } = response.data;

				lockScreen(false);

				if (currency) {
					$('#myCurrency').html(currency);
				}

				showAlert(title, message, success ? 'success' : 'danger');
				if (redirect) {
					setTimeout(() => {
						window.location.href = redirect;
					}, 1000);
				}
			} catch (err) {
				lockScreen(false);

				const { title, message } = err.response.data;
				showAlert(title, message, 'error');
			}
		});

		$('.upgrade', userBuildings).on('click', async () => {
			lockScreen(true);

			const buildingId = $(this).data('id');
			try {
				const response = await axiosInstance.post('/building/upgrade', { id: buildingId });
				const { title, message, redirect, success, currency } = response.data;

				lockScreen(false);

				if (currency) {
					$('#myCurrency').html(currency);
				}

				showAlert(title, message, success ? 'success' : 'danger');
				if (redirect) {
					setTimeout(() => {
						window.location.href = redirect;
					}, 1000);
				}
			} catch (err) {
				lockScreen(false);

				const { title, message } = err.response.data;
				showAlert(title, message, 'error');
			}
		});

		$('.repair', userBuildings).on('click', async () => {
			lockScreen(true);

			const buildingId = $(this).data('id');
			try {
				const response = await axiosInstance.post('/building/repair', { id: buildingId });
				const { title, message, redirect, success, currency } = response.data;

				lockScreen(false);

				if (currency) {
					$('#myCurrency').html(currency);
				}

				showAlert(title, message, success ? 'success' : 'danger');
				if (redirect) {
					setTimeout(() => {
						window.location.href = redirect;
					}, 1000);
				}
			} catch (err) {
				lockScreen(false);

				const { title, message } = err.response.data;
				showAlert(title, message, 'error');
			}
		});

		$('.sell', userBuildings).on('click', async () => {
			const buildingId = $(this).data('id');
			showAlert('Coming soon!', 'This feature is not available yet', 'info');
		});
	}
})();
