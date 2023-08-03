@extends('layouts.simple.master')
@section('title', 'Sample Page')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Sample Page</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Pages</li>
<li class="breadcrumb-item active">Sample Page</li>
@endsection

@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header">
               <h5 class="text-center">ABOUT US</h5>
            </div>
            <div class="card-body">
               <p>Working with clients for more than 15 years is a great sign of trust, confidence and integrity. From a simple tax return filing to completing assessments is quite a journey and gives the strength to decode the complexities further. We are proud of our happy customers who have expressed their great joy and satisfaction in working with us and who have achieved their goals while investing through us. In continuation of our journey, Optymoney is an integrated platform for individual financial management. Optymoney automates each activity with an option to avail advanced and confidential personalised services in a secured and simplified manner. Optymoney and its team of experts brings to you tools and information to help you achieve your financial goals and making compliances, savings and investing profitable, fun and stress free.</p>
               <div class="row md-3">
                  <div class="col-md-4">
                     <h5 class="text-center h2 txt-secondary">OUR CORE VALUES</h5>
                     <p>Customer first, Real Time and Strategic Support, Data and Information confidentiality, sound, transparent and unbiased offering</p>
                  </div>
                  <div class="col-md-4">
                     <h5 class="text-center h2 txt-secondary">OUR VISION</h5>
                     <p>To create a global brand respected by all for its integrity and value system, its people and excellence in service through knowledge and perseverance.</p>
                  </div>
                  <div class="col-md-4">
                     <h5 class="text-center h2 txt-secondary">OUR MISSION</h5>
                     <p>To enable our customers reach their financial independence and achieve their financial goals.</p>
                  </div>
               </div>
               <br><br>
               <div class="row md-3">
                  <div class="col-md-12">
                     <h5 class="text-center h2 txt-secondary">OUR SOCIAL INITIATIVES</h5>
                     <p>Contributing to society and human well being is a passionate drive and no compulsion for us. We believe in empowering youth and unblocking the potential of Generation Y. Our mainstream consists of young workforce, properly trained and organised for highest productivity. Happy customers, happy employees and happy vendors, inclusive workforce, always at par with the industry in terms of standards and technology - This is the most gratifying about work.</p>
                  </div>
               </div>
               <br><br>
               <div class="row md-3">
                  <div class="col-md-3"></div>
                  <div class="col-md-6">
                     <h5 class="text-center h2 txt-secondary">OUR COMMITMENT</h5>
                     <p><img src="https://optymoney.com/static/opty_theme/img/optyCommitment.svg"></p>
                  </div>
                  <div class="col-md-3"></div>
               </div>
               <br><br>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('script')
@endsection