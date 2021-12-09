@if ($paginator->hasPages())
	<ul class="pagination">
		{{-- Previous Page Link --}}
		@if ($paginator->onFirstPage())
			<li class="page-item disabled" aria-disabled="true">
				<a class="page-link"><i class="fe-arrow-left"></i></a>
			</li>
		@else
			<li class="page-item">
				<a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="fe-arrow-left"></i></a>
			</li>
		@endif

		{{-- Next Page Link --}}
		@if ($paginator->hasMorePages())
			<li class="page-item">
				<a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="fe-arrow-right"></i></a>
			</li>
		@else
			<li class="page-item disabled" aria-disabled="true">
				<a class="page-link"><i class="fe-arrow-right"></i></a>
			</li>
		@endif
	</ul>
@endif
