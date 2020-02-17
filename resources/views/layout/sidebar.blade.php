<?php  $desig_permissions = session()->get('desig_permission'); // assigning desig_permission so we can use ?>
<div class="sidebar sidebar-style-2" data-background-color="dark2">
	<div class="sidebar-wrapper scrollbar scrollbar-inner">
		<div class="sidebar-content">
			<ul class="nav nav-primary">
				<li class="nav-item"><a href="{{url(session()->get('dashboard_url'))}}"><i class="fas fa-home"></i>
					@if(session()->get('dashboard_title')=='My District')
					<p>  {{$phrase->district}}</p>
					@elseif(session()->get('dashboard_title')=='My SubDivision')
					<p>   {{$phrase->sub_divisin}}</p>
					@elseif(session()->get('dashboard_title')=='My Block')
					<p>   {{$phrase->block}}</p>
					@else
					<p>   {{$phrase->panchayat}}</p>
					@endif
				</a></li>
				
				@if(array_key_exists("mod13",$desig_permissions) || array_key_exists("mod14",$desig_permissions))
				<li class="nav-item">
					<a data-toggle="collapse" href="#asset" class="collapsed" aria-expanded="false">
						<i class="fas fa-layer-group"></i>
						<p>{{$phrase->resource}}</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="asset">
						<ul class="nav nav-collapse">
							@if(array_key_exists("mod13", $desig_permissions))
							<li class="nav-item"><a href="{{url('asset')}}"><i class="fas fa-list"></i><p>{{$phrase->define_resource}}</p></a></li>
							@endif
							@if(array_key_exists("mod14", $desig_permissions))
							<li class="nav-item"><a href="{{url('asset-numbers')}}"><i class="fas fa-list-ol"></i><p>{{$phrase->resource_number}} </p></a></li>
							@endif</ul>
					</div>
				</li>
				@endif

				@if(array_key_exists("mod15",$desig_permissions) || array_key_exists("mod16",$desig_permissions) || array_key_exists("mod18",$desig_permissions))
				<li class="nav-item">
					<a data-toggle="collapse" href="#scheme" class="collapsed" aria-expanded="false">
							<i class="fas fa-bezier-curve"></i>
						<p>{{$phrase->scheme}}</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="scheme">
						<ul class="nav nav-collapse">
							@if(array_key_exists("mod15", $desig_permissions))
							<li class="nav-item"><a href="{{url('scheme-structure')}}"><i class="fas fa-receipt"></i><p>{{$phrase->define_scheme}}</p></a></li>
							@endif
							@if(array_key_exists("mod16", $desig_permissions))
							<li class="nav-item"><a href="{{url('scheme-asset')}}"><i class="fas fa-users-cog"></i><p>{{$phrase->scheme_assets}}</p></a></li>
							@endif
							@if(array_key_exists("mod18", $desig_permissions))
							<li class="nav-item"><a href="{{url('scheme-performance')}}"><i class="fas fa-receipt"></i><p>{{$phrase->scheme_performance}}</p></a></li>
							@endif
						</ul>
					</div>
				</li>
				@endif

				@if(array_key_exists("mod26",$desig_permissions) || array_key_exists("mod23",$desig_permissions))
				<li class="nav-item">
					<a data-toggle="collapse" href="#duplicacy" class="collapsed" aria-expanded="false">
					<i class="fa fa-clone" aria-hidden="true"></i>
						<p>Duplicacy</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="duplicacy">
						<ul class="nav nav-collapse">
						@if(array_key_exists("mod16",$desig_permissions))
						<li class="nav-item"><a href="{{url('matching-schemes')}}"><i class="fa fa-check" aria-hidden="true"></i><p>Matching Schemes</p></a></li> 
						@endif
						@if(array_key_exists("mod23",$desig_permissions))
						<li class="nav-item"><a href="{{url('scheme-review/duplicate-review')}}"><i class="fa fa-clone" aria-hidden="true"></i><p>Duplicate Review</p></a></li>
						@endif
						</ul>
					</div>
				</li>
				@endif

				@if(array_key_exists("mod19",$desig_permissions) || array_key_exists("mod20",$desig_permissions))
				<li class="nav-item">
					<a data-toggle="collapse" href="#review" class="collapsed" aria-expanded="false">
					<i class="fas fa-chart-bar"></i>
						<p>{{$phrase->review}}</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="review">
						<ul class="nav nav-collapse">
							@if(array_key_exists("mod19", $desig_permissions))
							<li class="nav-item"><a href="{{url('asset-review')}}"><i class="fas fa-chart-bar"></i><p>{{$phrase->resource_review}} </p></a></li>
							@endif
							@if(array_key_exists("mod20", $desig_permissions))
							<li class="nav-item"><a href="{{url('scheme-review')}}"><i class="fas fa-chart-bar"></i><p>{{$phrase->scheme_review}}</p></a></li>
							@endif
						
					</div>
				</li>
				@endif


				@if(array_key_exists("mod24",$desig_permissions) || array_key_exists("mod25",$desig_permissions))
				<li class="nav-item">
					<a data-toggle="collapse" href="#Import" class="collapsed" aria-expanded="false">
							<i class="fas fa-file-import"></i>
						<p>{{$phrase->import}}</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="Import">
						<ul class="nav nav-collapse">
							@if(array_key_exists("mod24",$desig_permissions))
							<li class="nav-item"><a href="{{url('asset_Numbers/changeViewforimport')}}"><i class="fas fa-file-import"></i><p> {{$phrase->resource}}  {{$phrase->import}}</p></a></li>
							@endif
							@if(array_key_exists("mod25",$desig_permissions))
							<li class="nav-item"><a href="{{url('import/scheme')}}"><i class="fas fa-file-import"></i><p> {{$phrase->scheme}} {{$phrase->import}}</p></a></li>
							@endif
						</ul>
					</div>
				</li>
				@endif


				@if(array_key_exists("mod22", $desig_permissions))
				<li class="nav-item"><a href="{{url('favourites')}}"><i class="fa fa-star" aria-hidden="true"></i><p>{{$phrase->favourite}}</p></a></li>
				@endif

				<!-- @if(session()->get('user_designation')=="1")
					<li class="nav-item"><a href="{{url('module')}}"><i class="fas fa-tasks"></i><p>{{$phrase->module}}</p></a></li>
					<li class="nav-item"><a href="{{url('designation-permission')}}"><i class="fas fa-users-cog"></i><p>{{$phrase->designation_permission}}</p></a></li>
				@endif -->

				

				@if(array_key_exists("mod1", $desig_permissions) || array_key_exists("mod12", $desig_permissions) || array_key_exists("mod2", $desig_permissions) || array_key_exists("mod3", $desig_permissions) || array_key_exists("mod5", $desig_permissions) || array_key_exists("mod6", $desig_permissions) || array_key_exists("mod8", $desig_permissions) || array_key_exists("mod9", $desig_permissions) ||array_key_exists("mod10", $desig_permissions) || array_key_exists("mod11", $desig_permissions))
				<li class="nav-item">
					<a data-toggle="collapse" href="#administator" class="collapsed" aria-expanded="false">
							<i class="fas fa-user-tie"></i>
						<p>{{$phrase->setting}}</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="administator">
						<ul class="nav nav-collapse">
							@if(array_key_exists("mod1", $desig_permissions))
								<li class="nav-item"><a href="{{url('user')}}"><i class="fas fa-users"></i><p>{{$phrase->user}}</p></a></li>
							@endif
							@if(array_key_exists("mod12", $desig_permissions))
							<li class="nav-item"><a href="{{url('geo-structure')}}"><i class="fas fa-map-marker-alt"></i><p>{{$phrase->geo_struture}}</p></a></li>
							@endif
							@if(array_key_exists("mod2", $desig_permissions))
								<li class="nav-item"><a href="{{url('department')}}"><i class="fas fa-sitemap"></i><p>{{$phrase->department}}</p></a></li>
							@endif
							@if(array_key_exists("mod3", $desig_permissions))
								<li class="nav-item"><a href="{{url('year')}}"><i class="fas fa-calendar-alt"></i><p>{{$phrase->year}}</p></a></li>
							@endif
							

							@if(array_key_exists("mod5", $desig_permissions))
								<li class="nav-item"><a href="{{url('designation')}}"><i class="fas fa-user-check"></i><p>{{$phrase->designation}}</p></a></li>
							@endif
							@if(array_key_exists("mod6", $desig_permissions))
								<li class="nav-item"><a href="{{url('scheme-type')}}"><i class="fas fa-receipt"></i><p>{{$phrase->scheme_type}}</p></a></li>
							@endif
							
							@if(array_key_exists("mod8", $desig_permissions))
								<li class="nav-item"><a href="{{url('assetcat')}}"><i class="fa fa-th"></i><p>{{$phrase->resource_catagory}}</p></a></li>
							@endif
							@if(array_key_exists("mod9", $desig_permissions))
								<li class="nav-item"><a href="{{url('asset_subcat')}}"><i class="fa fa-th-large"></i><p>{{$phrase->resourcesub_catagory}}</p></a></li>
							@endif
							<!-- @if(array_key_exists("mod10", $desig_permissions))
							<li class="nav-item"><a href="{{url('module')}}"><i class="fas fa-tasks"></i><p>{{$phrase->module}}</p></a></li>
							@endif
							@if(array_key_exists("mod11", $desig_permissions))
							<li class="nav-item"><a href="{{url('designation-permission')}}"><i class="fas fa-users-cog"></i><p>{{$phrase->designation_permission}}</p></a></li>
							@endif -->
							@if(array_key_exists("mod4", $desig_permissions))
								<li class="nav-item"><a href="{{url('uom')}}"><i class="fa fa-balance-scale" aria-hidden="true"></i><p>{{$phrase->uom}}</p></a></li>
							@endif
							@if(array_key_exists("mod22", $desig_permissions))
								<li class="nav-item"><a href="{{url('uom_type')}}"><i class="fa fa-balance-scale" aria-hidden="true"></i><p>{{$phrase->uom_type}}</p></a></li>
							@endif
							
						</ul>
					</div>
				</li>
				@endif
			
		</div>
	</div>
</div>