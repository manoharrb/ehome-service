@extends('layouts.admin')
@section('title', 'Member')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Member</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active">Member</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->	
	<!-- Breadcrumb start-->
	<!--<ol class="breadcrumb">
		<li class="breadcrumb-item active">
			Home / <b>Dashboard</b>
		</li>
		@include('../Elements/Admin/breadcrumb')
	</ol>-->
	<!-- Breadcrumb end-->
	
	<!-- Main content --> 
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<!-- Flash Message Start -->
					<div class="server-error">
						@include('../Elements/flash-message')
					</div>
					<!-- Flash Message End -->
				</div>
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">  
							<div class="card-title">
								<a href="{{route('admin.member.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>
							</div> 
							<div class="card-tools card_tools">
								<form action="{{route('admin.member.index')}}">
								<div class="input-group input-group-sm" style="width: 250;">
								
									<input type="text" name="s" class="form-control float-right" value="{{@$_GET['s']}}" placeholder="Search">
									<div class="input-group-append">
										<button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
									</div>
									@if(isset($_GET['s']))
										<a style="margin-left: 4px;" class="btn btn-primary" href="{{route('admin.member.index')}}">Reset</a>
									@endif
									
								</div>
								</form>
							</div>
						</div>
						<div class="card-body table-responsive p-0">
							<table class="table table-hover text-nowrap">
							  <thead>
								<tr>
								  <th>ID</th>
								  <th>Name</th>
								  <th>Email</th>
								  <th>Phone</th>
								  <th>Action</th>
								</tr>  
							  </thead> 
							  <tbody class="tdata">	
							   @if(@$totalData !== 0)
								@foreach (@$lists as $list)	
								<tr id="id_{{@$list->id}}"> 
								  <td><a href="{{URL::to('/admin/member/show/'.base64_encode(convert_uuencode(@$list->id)))}}">{{ @$list->id }}</a></td> 
								  <td>
								<a href="{{URL::to('/admin/member/show/'.base64_encode(convert_uuencode(@$list->id)))}}">{{ @$list->title }}  {{ @$list->owner_name == "" ? config('constants.empty') : str_limit(@$list->owner_name, '50', '...') }}</a>
								 </td> 
								  <td>{{ @$list->email == "" ? config('constants.empty') : str_limit(@$list->email, '50', '...') }}</td> 
								  <td>{{ @$list->phone == "" ? config('constants.empty') : str_limit(@$list->phone, '50', '...') }}</td>
								  <td></td>
								  <td></td>
								  <td>
									<div class="nav-item dropdown action_dropdown">
										<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
										<div class="dropdown-menu">
											<a href="{{URL::to('/admin/member/edit/'.base64_encode(convert_uuencode(@$list->id)))}}"><i class="fa fa-edit"></i> Edit</a>
											<a href="{{URL::to('/admin/member/insurance-history/'.base64_encode(convert_uuencode(@$list->id)))}}"><i class="fa fa-edit"></i> Insurance History</a>  
											<a href="javascript:;" onClick="deleteAction({{@$list->id}}, 'members')"><i class="fa fa-trash"></i> Delete</a>  
										</div> 
									</div>
								  </td> 
								</tr>	
							  @endforeach 
									
							  </tbody>
							    @else
							  <tbody>
									<tr>
										<td style="text-align:center;" colspan="6">
											No Record found
										</td>
									</tr>
								</tbody>
							  @endif 
							</table> 
							<div class="card-footer">
							   {!! $lists->appends(\Request::except('page'))->render() !!}   
							 </div> 
						  </div>
					</div>	
				</div>	
			</div>
		</div>
	</section>
</div>
@endsection