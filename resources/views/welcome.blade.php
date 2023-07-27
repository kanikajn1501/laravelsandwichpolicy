@extends('layouts/header')
<h1>Employees</h1>

@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
</div>
@endif
@if($message = Session::get('error'))
<div class="alert alert-info alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
</div>
@endif
<table width="100%" border="1"  cellspacing="15">
<thead class="thead-dark">

          <tr> 
            
            <th>Name</th>
            <th>Leave Days</th>
            
            <th>Active</th>
            <th>Sandwich Status</th>
            <th>Created</th>
            <th>Action</th>
          </tr>
  </thead>
  @if($employees)
  @foreach($employees as $employee)
    <tr>
    
    <td>{{$employee->name}}</td>
    <td width ="20%">
     
      @if($employee->leaveDays)
        @foreach($employee->leaveDays as $leaveDay)
          {{$leaveDay}}
        @endforeach

      @endif
  
    </td>
    
    <td>{{$employee->active ? 'Active' : 'Not'}}</td>
    <td bgColor="{{$employee->bgColor}}">{{$employee->sandwichStatus}}</td>
    <td>{{\Carbon\Carbon::parse($employee->created_at)->format('d/m/Y')}}</td>
    <td><a href="{{ route('apply_leave',$employee->id) }}" class="btn btn-primary">Apply Leave</a></td>
    </tr>
    @endforeach
    @endif
</table>

 