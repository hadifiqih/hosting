@extends('layouts.app')

@section('title', 'Antrian | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian')

@section('breadcrumb', 'Antrian Stempel')

@section('content')
<link  href="https://cdn.jsdelivr.net/npm/nanogallery2@3/dist/css/nanogallery2.min.css" rel="stylesheet" type="text/css">
<div class="container">
    <div class="row">
        <div id='dokumentasiGallery'></div>
    </div>
</div>
@endsection

@section('script')
<script  type="text/javascript" src="https://cdn.jsdelivr.net/npm/nanogallery2@3/dist/jquery.nanogallery2.min.js"></script>
<script>
    $(document).ready(function () {

       $("#dokumentasiGallery").nanogallery2( {
           // ### gallery settings ### 
           thumbnailHeight:  150,
           thumbnailWidth:   150,
           itemsBaseURL:     'https://nanogallery2.nanostudio.org/samples/',
           
           // ### gallery content ### 
           items: [
               { src: 'berlin1.jpg', srct: 'berlin1_t.jpg', title: 'Berlin 1' },
               { src: 'berlin2.jpg', srct: 'berlin2_t.jpg', title: 'Berlin 2' },
               { src: 'berlin3.jpg', srct: 'berlin3_t.jpg', title: 'Berlin 3' }
             ]
         });
     });
 </script>
@endsection