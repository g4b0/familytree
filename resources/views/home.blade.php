@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in!<br>
                    {{ $Gabo->firstname }} {{ $Gabo->surname }}<br>
                    @if (isset($Father))
                        Father: {{ $Father->firstname }} {{ $Father->surname }}<br>
                        @if (isset($Grandfather))
                            Grandfather: {{ $Grandfather->firstname }} {{ $Grandfather->surname }}
                        @endif
                    @endif
                    @if (isset($Mother))
                        Mother: {{ $Mother->firstname }} {{ $Mother->surname }}<br>
                    @endif
                    @foreach ($Path as $p) 
                    Path: {{ $p }}<br>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
