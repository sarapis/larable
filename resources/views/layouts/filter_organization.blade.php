<style type="text/css">
	button[data-id="organization_tag"] {
        height: 45px;
        border: 1px solid #ddd;
    }
    .bootstrap-select .dropdown-toggle .filter-option {
    	display: flex;
    	align-items: center;
    	font-size: 12px;
    }
    .organization-tags-div {
    	height: 45px;
    }

</style>
<form action="/search_organization" method="GET" id="filter_organization">
  	<div class="filter-bar container-fluid bg-secondary" style="padding: 14px;    background-color: #abcae9 !important;">
		<div class="row">
			<div class="col-md-2 col-sm-2"></div>
			<div class="col-md-8 col-sm-8 col-xs-12">
				<div class="row">
					<div class="col-md-2">
						<div class="col-md-8 pt-15 pb-15 pl-15">
				            <div class="btn-group dropdown btn-feature">
				                <button type="button" class="btn btn-primary dropdown-toggle btn-button"  id="exampleSizingDropdown2" data-toggle="dropdown" aria-expanded="false">
				                    Sort by Updated
				                </button>
				                <div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown2" role="menu">
				                    <a @if(isset($sort) && $sort == 'Most Recently Updated') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Most Recently Updated</a>               
				                    <a @if(isset($sort) && $sort == 'Least Recently Updated') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Least Recently Updated</a>
				                </div>
				            </div>
				        </div>
					</div>
		          	<input type="hidden" name="meta_status" id="status" @if(isset($meta_status)) value="{{$meta_status}}" @else value="On" @endif>
					<div class="col-md-4 m-auto">
						<div class="input-search">
							<i class="input-search-icon md-search" aria-hidden="true"></i>
							<input type="text" class="form-control search-form" name="find" placeholder="Search for Organization" id="search_organization" @if(isset($chip_organization)) value="{{$chip_organization}}" @endif>
						</div>    
					</div>
					<div class="col-md-4 m-auto">
						<div class="organization-tags-div">
		                    <select class="form-control selectpicker" multiple data-live-search="true" id="organization_tag" data-size="3" name="organization_tag[]">
		                    	<option value="">Filter by Tag</option>
		                        @foreach($organization_tag_list as $key => $organization_tag)                                
		                            <option value="{{$organization_tag}}">{{$organization_tag}}</option>
		                        @endforeach
		                    </select>
		                </div>  
		            </div>
					<div class="col-md-2 m-auto">
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