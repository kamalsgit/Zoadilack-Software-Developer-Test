@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Zoadilack Database ​Settings</h2>
            </div>            
        </div>
    </div>   
    <table class="table">
        <thead class="thead-default">
        <tr>
            <th>MySQL</th>            
            <th>SQLITE</th>
        </tr>
        </thead>
        <tr>
            <td>
                <table class="table table-striped">
                    @foreach ($connection['connections']['mysql'] as $key=>$conn)
                    <tr>
                        <td>{{ $key}}</td>
                        <td>{{ $conn}}</td>
                    </tr>
                     @endforeach
                </table>            
            </td>  

            <td>
                <table class="table table-striped">
                    @foreach ($connection['connections']['sqlite2'] as $key=>$conn)
                    <tr>
                        <td>{{ $key}}</td>
                        <td>{{ $conn}}</td>
                    </tr>
                     @endforeach
                </table>            
            </td>            
        </tr>
    </table> 


    {{ Session::get('mysql_success') }}
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Zoadilack Email ​Settings Using devdb1 (MySQL) database</h2>
            </div>            
        </div>
    </div>

    <!-- MySQL Form -->
    
    <form action="zoadilack/notify" method="post">
        {{csrf_field()}}
        <div class="form-group">
            <label for="exampleInputEmail1">From Email address</label>
            <input type="email" class="form-control" id="email_from" name="email_from" aria-describedby="emailHelp" placeholder="From Email address" value="{{$sqlResult[0]->email_from}}">            
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Email Subject</label>
            <input type="text" class="form-control" id="email_subject" name="email_subject" aria-describedby="emailHelp" placeholder="Email Subject" value="{{$sqlResult[0]->email_subject}}">            
        </div>
        
        <button type="submit" name="dbName" value="mysql" class="btn btn-primary">Submit</button>
    </form>
    
    <!-- SQLITE Form -->    
    {{ Session::get('sqlite_success') }}
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Zoadilack Email ​Settings Using devdb2 (Sqlite) database</h2>
            </div>            
        </div>
    </div> 
    
    <form action="zoadilack/notify" method="post">
        {{csrf_field()}}
        <div class="form-group">
            <label for="exampleInputEmail1">Email Template</label>
            <input type="text" class="form-control" id="email_body_template" name="email_body_template" aria-describedby="emailHelp" placeholder="Email Template" value="{{$resultsSqlite[0]->email_body_template}}">         
        </div>               
        <button type="submit" name="dbName" value="sqlite" class="btn btn-primary">Submit</button>
    </form> 
    
    
    <!-- Email Send -->    
    {{ Session::get('email_success') }}
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Send Email</h2>
            </div>            
        </div>
    </div> 
    
    <form action="zoadilack/notify" method="post">
        {{csrf_field()}}
        <div class="form-group">
            <label for="exampleInputEmail1">Eamil</label>
            <input type="text" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Email" value="">         
        </div>               
        <button type="submit" name="dbName" value="email" class="btn btn-primary">Submit</button>
    </form>
    
    
@endsection