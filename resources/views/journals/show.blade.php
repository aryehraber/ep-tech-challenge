@extends('layouts.app')

@section('content')
<div class="container">
    <journal-show :client='@json($client)' :journal='@json($journal)'></journal-show>
</div>
@endsection
