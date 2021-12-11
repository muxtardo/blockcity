<div class="card card-body">
	<h5 class="card-title text-uppercase">{{ __('Verify Transaction') }}</h5>
	<form class="d-grid" id="form-consult" onsubmit="return false;">
		<div class="form-group">
			<input type="text" name="hash" class="form-control hash mb-2" placeholder="{{ __('Transaction Hash') }}" required />
		</div>
		<button type="submit" class="btn btn-primary waves-effect waves-light">{{ __('Check this Hash') }}</button>
	</form>
</div>
