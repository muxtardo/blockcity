<div class="card card-body">
	<h5 class="card-title text-uppercase">{{ __('Withdraw Coins to Tokens') }}</h5>
	<p class="card-text text-center"><b>{{ __('Available') }}:</b> <span id="myCurrency">{{ currency(Auth::user()->currency) }}</span> {{ __('Coins') }}</p>
	<form class="d-grid" id="form-withdrawal" onsubmit="return false;">
		<div class="form-group">
			<input type="text" data-toggle="input-money" name="amount" class="form-control amount mb-2" placeholder="{{ __('Amount') }}" required />
		</div>
		<button type="submit" class="btn btn-primary waves-effect waves-light">{{ __('Withdrawal') }}</button>
	</form>
</div>
