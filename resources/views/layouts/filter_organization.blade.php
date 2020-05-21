
<form action="/search_organization" method="GET" id="filter_organization">
  	<div class="filter-bar container-fluid bg-secondary" style="padding: 14px;    background-color: #abcae9 !important;">
		<div class="row">
			<div class="col-md-2 col-sm-2"></div>
			<div class="col-md-8 col-sm-8 col-xs-12">
				<div class="row">
		          	<input type="hidden" name="meta_status" id="status" @if(isset($meta_status)) value="{{$meta_status}}" @else value="On" @endif>
					<div class="col-md-8">
						<div class="input-search">
							
							<i class="input-search-icon md-search" aria-hidden="true"></i>
							<input type="text" class="form-control search-form" name="find" placeholder="Search for Organization" id="search_organization" @if(isset($chip_organization)) value="{{$chip_organization}}" @endif>
						</div>    
					</div>
					
					<div class="col-md-2">
						<button class="btn btn-primary btn-block waves-effect waves-classic btn-button" title="Search" style="line-height: 31px;">Search</button>
					</div>
				</div>
			</div>  
		</div>
  	</div>

<style>
@media (max-width: 768px){
  .filter-bar{
    display: none;
  }
}
</style>

<script type="text/javascript">
$(document).ready(function(){
	$('.dropdown-status').click(function(){
		var status = $(this).attr('at');
		var status_meta = $(this).html();
		$("#meta_status").html(status_meta);
		$("#status").val(status);
		$("#filter_organization").submit();
	});
});
</script>