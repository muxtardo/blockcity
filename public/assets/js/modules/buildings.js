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

		const mintTemplate = `<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-body">
						<div class="card-body product-box">
							<div class="bg-sky text-center d-flex align-items-center justify-content-center" style="min-height: 340px; position: relative;">
								<div class="stars-content">
									<i class="fa fa-star stars star-1"></i>
									<i class="fa fa-star stars star-2"></i>
									<i class="fa fa-star stars star-3"></i>
								</div>
								<img src="{image}" alt="product-pic" class="img-fluid">
								<div class="buyHouse-name">{name}</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>`;

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
