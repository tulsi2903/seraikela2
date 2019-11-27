<div class="sidebar sidebar-style-2" data-background-color="dark2">
	<div class="sidebar-wrapper scrollbar scrollbar-inner">
		<div class="sidebar-content">
			<ul class="nav nav-primary">
				<li class="nav-item">
					<a data-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
						<i class="fas fa-home"></i>
						<p>Dashboard</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="dashboard">
						<ul class="nav nav-collapse">
							<li><a href="{{url('dc_dashboard')}}"><span class="sub-item">DC Dashboard</span></a></li>
							<li><a href="{{url('#')}}"><span class="sub-item">BDO Dashboard</span></a></li>
							<li><a href="{{url('#')}}"><span class="sub-item">PO Dashboard</span></a></li>

						</ul>
					</div>
				</li>

				<li class="nav-item">
					<a data-toggle="collapse" href="#administator" class="collapsed" aria-expanded="false">
						<i class="fas fa-home"></i>
						<p>Administrator</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="administator">
						<ul class="nav nav-collapse">
							
							<li class="nav-item"><a href="{{url('user')}}"><i class="fas fa-users"></i><p>User</p></a></li>
							<li class="nav-item"><a href="{{url('department')}}"><i class="fas fa-sitemap"></i><p>Department</p></a></li>
							<li class="nav-item"><a href="{{url('year')}}"><i class="far fa-calendar-alt"></i><p>Year</p></a></li>
							<li class="nav-item"><a href="{{url('uom')}}"><i class="fas fa-receipt"></i><p>UoM</p></a></li>
                            <li class="nav-item"><a href="{{url('module')}}"><i class="fas fa-receipt"></i><p>Module</p></a></li>
                            <li class="nav-item"><a href="{{url('designation')}}"><i class="fas fa-receipt"></i><p>Define Designation</p></a></li>
				            <li class="nav-item"><a href="{{url('designation-permission')}}"><i class="fas fa-receipt"></i><p>Designation Permission</p></a></li>
						</ul>
					</div>
				</li>

				 <li class="nav-item"><a href="{{url('geo-structure')}}"><i class="fas fa-receipt"></i><p>Geo Structure</p></a></li>     


				<li class="nav-item">
					<a data-toggle="collapse" href="#asset" class="collapsed" aria-expanded="false">
						<i class="fas fa-home"></i>
						<p>Asset</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="asset">
						<ul class="nav nav-collapse">
							<li class="nav-item"><a href="{{url('asset')}}"><i class="fas fa-receipt"></i><p>Define Asset</p></a></li>
							<li class="nav-item"><a href="{{url('asset-numbers')}}"><i class="fas fa-receipt"></i><p>Asset Numbers</p></a></li>
						</ul>
					</div>
				</li>

				<li class="nav-item">
					<a data-toggle="collapse" href="#scheme" class="collapsed" aria-expanded="false">
						<i class="fas fa-home"></i>
						<p>Scheme</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="scheme">
						<ul class="nav nav-collapse">
							 <li class="nav-item"><a href="{{url('scheme-structure')}}"><i class="fas fa-receipt"></i><p>Define Scheme</p></a></li>
							<li class="nav-item"><a href="{{url('scheme-group')}}"><i class="fas fa-receipt"></i><p>Scheme Group</p></a></li>
			                <li class="nav-item"><a href="{{url('scheme-type')}}"><i class="fas fa-receipt"></i><p>Scheme Type</p></a></li>
			                <li class="nav-item"><a href="{{url('scheme-indicator')}}"><i class="fas fa-receipt"></i><p>Scheme Indicator</p></a></li>
			                <li class="nav-item"><a href="{{url('scheme-geo-target')}}"><i class="fas fa-receipt"></i><p>Scheme Geo Target</p></a></li>
						</ul>
					</div>
				</li>
               
			</ul>
		</div>
	</div>
</div>