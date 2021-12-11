@extends('partials/master')

@section('content')
	@include('dashboard/partials/modal')

	<div class="row user-buildings">
		<div class="col-lg-3">
			<div class="user-houses-stats mb-2">
				<div>
					@include('dashboard.partials.mint')
					@include('dashboard.partials.widget', [
						'icon'		=> 'fe-dollar-sign',
						'color'		=> 'success',
						'id'		=> 'myCurrency',
						'counter'	=> currency(Auth::user()->currency),
						'title'		=> __('Coins')
					])
					@include('dashboard.partials.widget', [
						'icon'		=> 'fe-home',
						'color'		=> 'info',
						'id'		=> 'myBuildings',
						'counter'	=> $totalBuildings,
						'title'		=> __('Total Houses')
					])
					@include('dashboard.partials.widget', [
						'icon'		=> 'fe-dollar-sign',
						'color'		=> 'dark',
						'id'		=> 'myDailyClaim',
						'counter'	=> currency(Auth::user()->maxDailyClaim()),
						'title'		=> __('Max Daily Claim')
					])
					@include('dashboard.partials.widget', [
						'icon'		=> 'fe-users',
						'color'		=> 'primary',
						'id'		=> 'myWorkers',
						'counter'	=> Auth::user()->workers(),
						'title'		=> __('Total Citizens')
					])
				</div>
			</div>
		</div>

		<div class="col-lg-9 building-list">
			@include('dashboard.partials.buildings')
		</div>
	</div>
@endsection

@section('js-libs')
	<script src="https://unpkg.com/vue@next"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment-with-locales.min.js"></script>
@endsection
@section('js')
	<script type="text/javascript">
		// momentJs setting prefferences
		moment.locale('{{ config('app.locale') }}');
		moment.relativeTimeThreshold('s', 3); 
		moment.relativeTimeThreshold('ss', 3); // less than 3 seconds hidden the number of seconds
		moment.relativeTimeThreshold('m', 59); // greater than 59 seconds hidden the number of minutes
		moment.relativeTimeThreshold('h', 23); // greater than 20 minutes hidden the number of hours

		const config = {
			trans: {
				confirmTitle: '{{ __("Are you sure?") }}',
				confirmMint: '{{ __("You are about to spend :currency coins to mint a new building!") }}',
				confirm: '{{ __("You are about to spend :currency coins to :action this house!") }}',
				button: '{{ __("Yes, :action it!") }}',
				repair: "{{ __('Repair') }}",
    			upgrade: "{{ __('Upgrade') }}",
				mint: "{{ __('Mint') }}",
			},
			mint_cost: {{ config('game.mint_cost') }},
			min_claim: {{ config('game.min_claim') }},
		};
	</script>
@endsection
