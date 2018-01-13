@extends('back')
@section('content')
    <div class="wrapper">



        <div class="main-panel">

            <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    
                    
   <div id="movie-menu-anch" class="hl-title movie-menu">
<div class="bd-container-inner">
<ul class=" bd-menu-5 nav navbar-left nav-pills">

<li class=" bd-menuitem-15 item-104 current">
<a href="{{ route('ipfilter') }}" class="title" style=" color:white;background: linear-gradient(transparent,rgba(0,0,0,0.8)); font-weight:bold;">IP Filter</a>
</li>


</ul>
</div>
</div>
 
         
 
           <div role="tabpanel" class="tab-pane" id="ip_filter">
            
            
            
            <div class="form-group">
                  <form action="{{ route('ipfilterupdate') }}" method="POST" style="float: left;width: 100%;">
                              {{ csrf_field() }}
                    <label for="" class="col-sm-3 control-label">Status</label>
                    <div class="col-sm-12">
                        <span>The Filter is: </span>
                        @if($status==0)
                         <button type="submit"class="btn btn-primary" style="background:#e1e1e1;color: red;border-color: red;"  name="status" value="1" >Off <i class="md md-lock-open"></i></button>
                         @else
                            <button type="submit"class="btn btn-primary" style="background:#e1e1e1;color: green;border-color: green;" name="status" value="0" >On <i class="md md-lock-open"></i></button>
                         @endif
                    </div>
                   
                       </form>
                </div>
           
                 
                <div class="form-group">
                  <form action="{{ route('ipfilterupdate') }}" method="POST" style="float: left;width: 100%;">
                              {{ csrf_field() }}
                    <label for="" class="col-sm-3 control-label">Redirect Url</label>
                    <div class="col-sm-6">
                        <input type="text" name="redirect_url" class="form-control" value= "{{ $redirect_banned }}"/>
                    </div>
                    <div class="col-sm-3 ">
                        <button type="submit"class="btn btn-primary"  value="Save" >Save <i class="md md-lock-open"></i></button>
                    </div>
                       </form>
                </div>
                
                <div class="form-group"  style="margin-top:35px;overflow-y: scroll;min-width: 200px;width: 98%;max-height: 500px;margin-top:35px;">
                  
                 <label for="" class="col-sm-1 control-label">No</label>
                    <label for="" class="col-sm-2 control-label">Whitelist IPs</label>
                       <label for="" class="col-sm-3 control-label">&nbsp;</label>
                       <label for="" class="col-sm-3 control-label">&nbsp;</label>
                       <label for="" class="col-sm-3 control-label">&nbsp;</label>
                       <div class="whiteform-cont">
                    @foreach($whitelisted as $key=>$ip)
                        <form action="{{ route('ipfilterupdate') }}" method="POST" style="float: left;width: 100%;">
                             {{ csrf_field() }}
                     <input type="hidden" name="ipid" class="form-control" value= "{{ $ip->id }}"/>
                     <label for="" class="col-sm-1 control-label">{{$key+1}}.</label>
                    <div class="col-sm-2">
                         <input type="text" name="whitelisted" class="form-control" value= "{{ $ip->ip }}"/>
                    </div>
                    <div class="col-sm-3">
                        <button type="submit" name="deletewl" value="Delete"  class="btn btn-primary">Delete</button>
                         <button type="submit" name="savewhitelisted" value="Save"  class="btn btn-primary">Save </button>
                    </div>
                    <div class="col-sm-3">
                     &nbsp;
                    </div>
                     <div class="col-sm-3">
                        &nbsp;
                    </div>
                         </form>
                    @endforeach
                    </div>
                 
                    
                </div>
                 <div class="col-sm-12">
                        <button type="submit" name="addwhitelisted" class="btn btn-primary addwhitelisted">Add New</button>
                        
                    </div>
                    
                    
                       <div class="form-group"  style="margin-top:35px;overflow-y: scroll;min-width: 200px;width: 98%;max-height: 500px;margin-top:35px;">
                  
                 <label for="" class="col-sm-1 control-label">No</label>
                    <label for="" class="col-sm-2 control-label">Banned Countries</label>
                       <label for="" class="col-sm-3 control-label">Country Code</label>
                       <label for="" class="col-sm-3 control-label">Banned At</label>
                       <label for="" class="col-sm-3 control-label">&nbsp;</label>
                       <div class="countryform-cont">
                    @foreach($banned_countries as $key=>$ip)
                        <form action="{{ route('ipfilterupdate') }}" method="POST" style="float: left;width: 100%;">
                             {{ csrf_field() }}
                     <input type="hidden" name="id" class="form-control" value= "{{ $ip->id }}"/>
                     <label for="" class="col-sm-1 control-label">{{$key+1}}.</label>
                    <div class="col-sm-2">
                         <input type="text" name="country" class="form-control" value= "{{ $ip->country }}"/>
                    </div>
                    <div class="col-sm-3">
                         <input type="text" name="country_code" class="form-control" value= "{{ $ip->country_code }}"/>
                    </div>
                    <div class="col-sm-3">
                         <input type="text" name="bandate" class="form-control" value= "{{ $ip->created_at }}"/>
                    </div>
                     <div class="col-sm-3">
                        <button type="submit" name="deletecountry" value="Delete"  class="btn btn-primary">Delete</button>
                         <button type="submit" name="savecountry" value="Save"  class="btn btn-primary">Save </button>
                    </div>
                         </form>
                    @endforeach
                    </div>
                 
                   
                </div>
                
                  <div class="col-sm-12">
                        <button type="submit" name="addnewcountry" class="btn btn-primary addcountry">Add New</button>
                    </div>
                    
                    
                <div class="form-group"  style="margin-top:35px;overflow-y: scroll;min-width: 200px;width: 98%;max-height: 500px;margin-top:35px;">
                  
                 <label for="" class="col-sm-1 control-label">No</label>
                    <label for="" class="col-sm-2 control-label">Banned IPs</label>
                       <label for="" class="col-sm-3 control-label">Reason</label>
                       <label for="" class="col-sm-3 control-label">Banned At</label>
                       <label for="" class="col-sm-3 control-label">&nbsp;</label>
                       <div class="banform-cont">
                    @foreach($banned as $key=>$ip)
                        <form action="{{ route('ipfilterupdate') }}" method="POST" style="float: left;width: 100%;">
                             {{ csrf_field() }}
                     <input type="hidden" name="ipid" class="form-control" value= "{{ $ip->id }}"/>
                     <label for="" class="col-sm-1 control-label">{{$key+1}}.</label>
                    <div class="col-sm-2">
                         <input type="text" name="banned" class="form-control" value= "{{ $ip->ip }}"/>
                    </div>
                    <div class="col-sm-3">
                         <input type="text" name="reason" class="form-control" value= "{{ $ip->reason }}"/>
                    </div>
                    <div class="col-sm-3">
                         <input type="text" name="bandate" class="form-control" value= "{{ $ip->created_at }}"/>
                    </div>
                     <div class="col-sm-3">
                        <button type="submit" name="deletebanned" value="Delete"  class="btn btn-primary">Delete</button>
                         <button type="submit" name="savebanned" value="Save"  class="btn btn-primary">Save </button>
                    </div>
                         </form>
                    @endforeach
                    </div>
                 
                   
                </div>
                  <div class="col-sm-12">
                        <button type="submit" name="addnewban" class="btn btn-primary addbanned">Add New</button>
                        
                    </div>
                
                     <script>
                    $('.addcountry').click(function(){
                        $('.countryform-cont').append('<form action="{{ route('ipfilterupdate') }}" method="POST" style="float: left;width: 100%;">{{ csrf_field() }}<label for="" class="col-sm-1 control-label">&nbsp;</label><div class="col-sm-2"><input type="text" name="country" class="form-control" value= ""/></div><div class="col-sm-3"><input type="text" name="country_code" class="form-control" value= ""/></div><div class="col-sm-3"><input type="text" name="bandate" class="form-control" value= "{{date("Y-m-d H:i:s", time())}}"/></div><div class="col-sm-3"><button type="submit" name="savecountry" value="Save" class="btn btn-primary">Save</button></div> </form> ');
                    });
                    $('.addbanned').click(function(){
                        $('.banform-cont').append('<form action="{{ route('ipfilterupdate') }}" method="POST" style="float: left;width: 100%;">{{ csrf_field() }}<label for="" class="col-sm-1 control-label">&nbsp;</label><div class="col-sm-2"><input type="text" name="banned" class="form-control" value= ""/></div><div class="col-sm-3"><input type="text" name="reason" class="form-control" value= "Admin"/></div><div class="col-sm-3"><input type="text" name="bandate" class="form-control" value= "{{date("Y-m-d H:i:s", time())}}"/></div><div class="col-sm-3"><button type="submit" name="savebanned" value="Save" class="btn btn-primary">Save</button></div> </form> ');
                    });
                      $('.addwhitelisted').click(function(){
                        $('.whiteform-cont').append('<form action="{{ route('ipfilterupdate') }}" method="POST" style="float: left;width: 100%;">{{ csrf_field() }}<label for="" class="col-sm-1 control-label">&nbsp;</label><div class="col-sm-2"><input type="text" name="whitelisted" class="form-control" value= ""/></div><div class="col-sm-3"><button type="submit" name="savewhitelisted" value="Save" class="btn btn-primary">Save</button></div><div class="col-sm-3">&nbsp;</div><div class="col-sm-3">&nbsp;</div> </form> ');
                    });
                </script>
                
                <div class="form-group"  style="margin-top:35px;overflow-y: scroll;min-width: 200px;width: 98%;max-height: 500px;margin-top:35px;">
                      
                 <label for="" class="col-sm-1 control-label">No</label>
                    <label for="" class="col-sm-2 control-label">Suspicious IPs</label>
                       <label for="" class="col-sm-3 control-label">URL</label>
                       <label for="" class="col-sm-3 control-label">Visited At</label>
                       <label for="" class="col-sm-3 control-label">&nbsp;</label>
                    @foreach($suspicious as $key=>$ip)
                    <form action="{{ route('ipfilterupdate') }}" method="POST" style="float: left;width: 100%;">
                         {{ csrf_field() }}
                     <label for="" class="col-sm-1 control-label">{{$key+1}}.</label>
                    <div class="col-sm-2">
                         <input type="text" name="visited[]" class="form-control" value= "{{ $ip->ip }}"/>
                    </div>
                    <div class="col-sm-3">
                         <input type="text" name="url[]" class="form-control" value= "{{ $ip->url }}"/>
                    </div>
                    <div class="col-sm-3">
                         <input type="text" name="visitdate[]" class="form-control" value= "{{ $ip->created_at }}"/>
                    </div>
                     <div class="col-sm-3">&nbsp;
                        <!--<button type="submit" name="deletevisited" class="btn btn-primary">Delete</button>-->
                        <!-- <button type="submit" name="savevisited" class="btn btn-primary">Save </button>-->
                    </div>
                     </form> 
                    @endforeach
                    
                </div>
                 
                <hr>
                

        
        </div>
        
    
    

                 
                </div>
            </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">

        //            $(document).ready(function() {
        //                $('#userlistadmin').DataTable( {
        //                    "order": [[ 2, "desc" ]]
        //                } );
        //            } );


        $(document).ready(function() {
            $('#userlistadmin').DataTable( {
                "pagingType": "5"
            } );
        } );
    </script>
@endsection