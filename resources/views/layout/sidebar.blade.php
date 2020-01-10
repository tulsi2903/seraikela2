<?php  $desig_permissions = session()->get('desig_permission'); // assigning desig_permission so we can use ?>
<div class="sidebar sidebar-style-2" data-background-color="dark2">
	<div class="sidebar-wrapper scrollbar scrollbar-inner">
		<div class="sidebar-content">
			<ul class="nav nav-primary">
				<li class="nav-item"><a href="{{url(session()->get('dashboard_url'))}}"><i class="fas fa-home"></i><p>{{session()->get('dashboard_title')}}</p></a></li>

				<li class="nav-item">
					<a data-toggle="collapse" href="#administator" class="collapsed" aria-expanded="false">
							<i class="fas fa-user-tie"></i>
						<p>Administrator</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="administator">
						<ul class="nav nav-collapse">
							@if(array_key_exists("user", $desig_permissions))
								<li class="nav-item"><a href="{{url('user')}}"><i class="fas fa-users"></i><p>User</p></a></li>
							@endif
							@if(array_key_exists("department", $desig_permissions))
								<li class="nav-item"><a href="{{url('department')}}"><i class="fas fa-sitemap"></i><p>Department</p></a></li>
							@endif
							<li class="nav-item"><a href="{{url('geo-structure')}}"><i class="fas fa-map-marker-alt"></i><p>Geo Structure</p></a></li>
							@if(array_key_exists("year", $desig_permissions))
								<li class="nav-item"><a href="{{url('year')}}"><i class="fas fa-calendar-alt"></i><p>Year</p></a></li>
							@endif
							@if(array_key_exists("uom", $desig_permissions))
								<!-- <li class="nav-item"><a href="{{url('uom')}}"><i class="fas fa-list-ol"></i><p>UoM</p></a></li> -->
							@endif
							@if(array_key_exists("designation", $desig_permissions))
								<li class="nav-item"><a href="{{url('designation')}}"><i class="fas fa-user-check"></i><p>Designation</p></a></li>
							@endif
							@if(array_key_exists("scheme type", $desig_permissions))
								<li class="nav-item"><a href="{{url('scheme-type')}}"><i class="fas fa-receipt"></i><p>Scheme Type</p></a></li>
							@endif
							@if(array_key_exists("scheme group", $desig_permissions))
								<li class="nav-item"><a href="{{url('scheme-group')}}"><i class="fas fa-receipt"></i><p>Scheme Group</p></a></li>
							@endif
							@if(array_key_exists("asset category", $desig_permissions))
								<li class="nav-item"><a href="{{url('assetcat')}}"><i class="fas fa-receipt"></i><p> Resource Category</p></a></li>
							@endif
							@if(array_key_exists("asset subcategory", $desig_permissions))
								<li class="nav-item"><a href="{{url('asset_subcat')}}"><i class="fas fa-receipt"></i><p> Resource Sub Category</p></a></li>
							@endif
							@if(session()->get('user_designation')=="1")
								<li class="nav-item"><a href="{{url('module')}}"><i class="fas fa-indent"></i><p>Module</p></a></li>
								<li class="nav-item"><a href="{{url('designation-permission')}}"><i class="fas fa-users-cog"></i><p>Designation Permission</p></a></li>
							@endif
						</ul>
					</div>
				</li>

				<li class="nav-item">
					<a data-toggle="collapse" href="#asset" class="collapsed" aria-expanded="false">
						<i class="fas fa-layer-group"></i>
						<p>Resources</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="asset">
						<ul class="nav nav-collapse">
							<li class="nav-item"><a href="{{url('asset')}}"><i class="fas fa-list"></i><p>Define Resources</p></a></li>
							<li class="nav-item"><a href="{{url('asset-numbers')}}"><i class="fas fa-list-ol"></i><p>Resources Number</p></a></li>
						</ul>
					</div>
				</li>

				<li class="nav-item">
					<a data-toggle="collapse" href="#scheme" class="collapsed" aria-expanded="false">
							<i class="fas fa-bezier-curve"></i>
						<p>Scheme</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="scheme">
						<ul class="nav nav-collapse">
							@if(array_key_exists("scheme", $desig_permissions))
								<li class="nav-item"><a href="{{url('scheme-structure')}}"><i class="fas fa-receipt"></i><p>Define Scheme</p></a></li>
							@endif
							<li class="nav-item"><a href="{{url('scheme-asset')}}"><i class="fas fa-users-cog"></i><p>Scheme Assets</p></a></li>
			                <!-- <li class="nav-item"><a href="{{url('scheme-geo-target')}}"><i class="fas fa-receipt"></i><p>Scheme Geo Target</p></a></li> -->
			                <li class="nav-item"><a href="{{url('scheme-performance')}}"><i class="fas fa-receipt"></i><p>Scheme Performance</p></a></li>
						</ul>
					</div>
				</li>

				<li class="nav-item">
					<a data-toggle="collapse" href="#review" class="collapsed" aria-expanded="false">
							<i class="fas fa-receipt"></i>
						<p>Review</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="review">
						<ul class="nav nav-collapse">
							<li class="nav-item"><a href="{{url('asset-review')}}"><i class="fas fa-receipt"></i><p>Resources Review</p></a></li>
							<li class="nav-item"><a href="{{url('review/scheme')}}"><i class="fas fa-receipt"></i><p>Scheme Review</p></a></li>
							<li class="nav-item"><a href="{{url('review/group')}}"><i class="fas fa-receipt"></i><p>Group Review</p></a></li>
						</ul>
					</div>
				</li>
				<li class="nav-item"><a href="{{url('favourites')}}"><i class="fa fa-bookmark" aria-hidden="true"></i><p>Favourite</p></a></li>
				<!-- <li class="nav-item"><a href="{{url('mgnrega')}}"><i class="fa fa-bookmark" aria-hidden="true"></i><p>Mgnrega</p></a></li> -->
			</ul>
		</div>
	</div>
</div>