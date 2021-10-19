@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="row no-gutters">
              <div class="col-md-3">
                <div class="image_blog"></div>
              </div>
              <div class="col-md-4">
                <div class="card-body">
                  <h5 class="card-title"><span class="original_title"></span></h5>
                  <p class="card-text"><span class="original_overview"></span></p>
                  <p class="card-text"><small class="text-muted">Release on date <span class="release_date"></span> </small></p>
                </div>
              </div>
              <div class="col-md-5">
                <div class="card-body">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">Popularity : <strong> <span class="popularity"></span> </strong> </li>
                    <li class="list-group-item">Language : <strong> <span class="original_language"></span> </strong> </li>
                    <li class="list-group-item">Runtime : <strong> <span class="runtime"></span> </strong> </li>
                    <li class="list-group-item">Budget : <strong> <span class="budget"></span> </strong> </li>
                    <li class="list-group-item">Revenue : <strong> <span class="revenue"></span> </strong> </li>
                    <li class="list-group-item">Vote Average : <strong> <span class="vote_average"></span> </strong> </li>
                    <li class="list-group-item">Vote Count : <strong> <span class="vote_count"></span> </strong> </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="row">
      <div class="col-md-12 my-1">
        <div class="alert alert-primary" role="alert">
          <h5 class="text-center">Production Companies</h5>
        </div>
      </div>
    </div>
    <div class="row production_companies"></div>
    <div class="row">
      <div class="col-md-12 my-1">
        <div class="alert alert-primary" role="alert">
          <h5 class="text-center">Production Countries</h5>
        </div>
      </div>
    </div>
    <div class="row production_countries"></div>
</div>
<script>
   $( document ).ready(function() {
     var base_url = {!! json_encode(url('/')) !!};
     $('body').tooltip({
         selector: '[data-toggle="tooltip"]'
     });
     function getData(){
       $.get(base_url+'/api/rest/movie-list',function(result){
         if (result.status == true) {
           $('.original_title').text(result.data.original_title)
           $('.original_overview').text(result.data.overview)
           $('.popularity').text(result.data.popularity)
           $('.original_language').text(result.data.original_language)
           $('.runtime').text(result.data.runtime)
           $('.budget').text(result.data.budget)
           $('.revenue').text(result.data.revenue)
           $('.vote_average').text(result.data.vote_average)
           $('.vote_count').text(result.data.vote_count)
           $('.release_date').text(result.data.release_date)
           var img = '<img src="https://image.tmdb.org/t/p/w500'+result.data.poster_path+'" width="100%" height="100%" />'
           $('.image_blog').html(img)
           var production_companies = '';
           if (result.data.production_companies) {
             $.each(result.data.production_companies, function(key,value){
               production_companies +='<div class="col-md-2">'+
                                            '<div class="card" style="height: 10rem;">'+
                                            '<img src="https://image.tmdb.org/t/p/w500'+value.logo_path+'" width="100%" height="50%">'+
                                            '<div class="card-body">'+
                                              '<p class="card-text">'+value.name+'</p>'+
                                            '</div>'+
                                            '</div>'+
                                          '</div>';
             })
           }
           $('.production_companies').html(production_companies)
           var production_countries = '';
           if (result.data.production_countries) {
             $.each(result.data.production_countries, function(key,value){
               production_countries +='<div class="col-md-2">'+
                                            '<div class="card" style="height: 10rem;">'+
                                            '<div class="card-body">'+
                                              '<p class="card-text">'+value.name+'</p>'+
                                            '</div>'+
                                            '</div>'+
                                          '</div>';
             })
           }
           $('.production_countries').html(production_countries)

         }
       });
     }
     getData()

   });
</script>
@endsection
