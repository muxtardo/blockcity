<div class="card card-body">
	<h5 class="card-title text-center">{{ __('Mint House') }}</h5>
	<p class="text-muted text-center">
		{{ __('Click on the button below to purchase a new home.') }}
	</p>
	<button v-on:click="doMint()" type="button" class="btn btn-primary waves-effect waves-light text-uppercase">
		<b>{{ __('New Mint') }}</b>
	</button>
</div>
