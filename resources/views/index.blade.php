@extends('layouts.app')

@section('bar')
    <add-todo-component></add-todo-component>
@endsection

@section('content')
    <todo-list-component></todo-list-component>
@endsection