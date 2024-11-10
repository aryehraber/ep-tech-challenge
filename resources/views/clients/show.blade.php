@extends('layouts.app')

@section('content')
<div class="container">
    <client-show :client='@json($client)' :booking-type='@json($bookingType)'></client-show>
</div>
@endsection
