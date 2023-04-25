@extends('root')
<head>
    <link rel="stylesheet" href="/resources/css/chatitem.css">
    <link rel="stylesheet" href="/resources/css/searchlist.css">
</head>
@livewire('incoming-call')
@section('content')
@livewire('conversation')
@endsection