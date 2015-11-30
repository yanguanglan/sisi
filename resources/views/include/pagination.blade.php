<div class="paginator">
	@if($posts->currentPage()<=1)
	<span class="paginate previous">上一页</span>
	@else
	<a href="{{$posts->previousPageUrl()}}" class="paginate newer">上一页</a>
	@endif

	@if(!$posts->hasMorePages())
	<span class="paginate next">下一页</span>
	@else
	<a href="{{$posts->nextPageUrl()}}" class="paginate older">上一页</a>
	@endif
</div>