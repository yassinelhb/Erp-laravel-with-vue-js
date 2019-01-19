@extends('layouts.nav')


@section('content')
    <dashborad :userauth="{{Auth::user()}}"></dashborad>
@endsection