<div class="card">
	<div class="card-body">
		<h4 class="header-title text-uppercase">{{ __('Account Transactions') }}</h4>
		<p class="sub-header">
			{{ __('List of transactions carried out on your account.') }}
		</p>

		<div class="table-responsive">
			<table class="table mb-0">
				<thead class="table-light">
					<tr>
						<th class="text-center">{{ __('Date') }}</th>
						<th class="text-center">{{ __('Action') }}</th>
						<th class="text-center">{{ __('Amount') }}</th>
						<th class="text-center">{{ __('Status') }}</th>
						<th class="text-center">{{ __('Link') }}</th>
						<th class="text-center">{{ __('Fee') }}</th>
						<th class="text-center"></th>
					</tr>
				</thead>
				<tbody>
					@foreach ($transactions as $transaction)
						<tr>
							<th class="text-center" scope="row">{{ $transaction->created_at }}</th>
							<td class="text-center">{{ Str::ucfirst($transaction->type) }}</td>
							<td class="text-center">{{ currency($transaction->amount) }}</td>
							<td class="text-center">
								<b class="text-{{ $transaction->statusColor() }}">{{ __(Str::ucfirst($transaction->status)) }}</b>
							</td>
							<td class="text-center">
								@if ($transaction->status == 'success' && $transaction->txid)
									<a href="https://testnet.bscscan.com/tx/{{ $transaction->txid }}" target="_blank">BscScan</a>
								@else
									--
								@endif
							</td>
							<td class="text-center">{{ $transaction->fee ? $transaction->fee . '%' : '--' }}</td>
							<td class="text-center">--</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div><!-- end table-responsive-->
	</div>
</div>
<div class="paginator-center">
	{!! $transactions->render() !!}
</div>
