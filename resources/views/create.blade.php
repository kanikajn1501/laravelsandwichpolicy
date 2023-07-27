@extends('layouts/header')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <div class="row">
            <div class="col-sm-12">  
                <h1>Apply Leave</h1>
        </div>
    </div>
    <div class="container">
    <form action="{{ route('submit_leave') }}" method="post">
    @csrf
       
        <div class="row">
            <div class="col-sm-12">        
                <div class="card border-primary mb-3">
                    <div class="card-body text-primary">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <div class="col-md-6">
                        <input type="hidden" value="{{$empId}}" class="form-control" id="empId" name="empId">
                        <div class="form-group">
                            <label for="startDate">From Date</label>
                            <input type="date" value="{{ old('startDate') }}" class="form-control" id="startDate" name="startDate" >
                        </div>
                        </div>  
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="endDate">To Date</label>
                            <input type="date" value="{{ old('endDate') }}" class="form-control" id="endDate" name="endDate" >
                        </div>
</div>
                    </div> 
                </div>         
            </div> 
            
        </div>
        <div class="row">
        <div class="col-sm-12"> 
            
                <button type="submit" name="btn-submit" value="1" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Submit</button>
           
        </div>
        </div>
        <div class="col-sm-12"> 
                <div class="row full-calendar">
                    <div class="col-sm-12">
                        <div id="calendar"></div>
                    </div>
                </div>
                <script src="{{ asset('js/calender.js') }}" defer></script>
            </div> 
        </div>
    </form>
</div>