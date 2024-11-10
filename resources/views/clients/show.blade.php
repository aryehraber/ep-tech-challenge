@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <client-show :client='@json($client)' :booking_type='@json($booking_type)'></client-show>
</div>
@endsection
