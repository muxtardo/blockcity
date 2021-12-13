<div class="user-transactions">
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
						<tr v-for="transaction in transactionsFiltered" :key="transaction.id">
							<th class="text-center" scope="row">@{{ transaction.created_at }}</th>
							<td class="text-center text-capitalize">@{{ transaction.type }}</td>
							<td class="text-center">@{{ transaction.amount }}</td>
							<td class="text-center">
								<b :class="'text-capitalize text-' + transaction.statusColor">@{{ transaction.status }}</b>
							</td>
							<td class="text-center">
								<template v-if="transaction.status.toLowerCase() == 'success' && transaction.txid">
									<a :href="linkBSC + '/tx/' + transaction.txid" target="_blank">
										BscScan
									</a>
								</template>
								<template v-else>--</template>
							</td>
							<td class="text-center">@{{ transaction.fee }}%</td>
							<td class="text-center">--</td>
						</tr>
					</tbody>
				</table>
			</div><!-- end table-responsive-->
		</div>
	</div>
	<div class="paginator-end" v-if="totalPages > 1">
		<ul class="pagination">
			<li class="page-item" :class="current_page <= 1 ? 'disabled' : ''" aria-disabled="true">
				<button class="page-link" v-on:click="nextPage(current_page - 1)"><i class="fe-arrow-left"></i></button>
			</li>
			<li class="page-item" :class="current_page >= totalPages ? 'disabled' : ''">
				<button class="page-link" v-on:click="nextPage(current_page + 1)" rel="next"><i class="fe-arrow-right"></i></button>
			</li>
		</ul>
	</div>
</div>

@section('js-libs')
	<script src="https://unpkg.com/vue@3.2.26/dist/vue.global.prod.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment-with-locales.min.js"></script>
@endsection
