@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="page-header">
                    <h1>
                        {{ $profileUser->name }}
                        <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
                    </h1>
                </div>

                @forelse($activities as $date => $record)
                    <h3 class="page-header">
                        {{ $date }}
                    </h3>
                    <!-- /.page-header -->
                    @foreach($record as $activity)
                        @if(view()->exists("profiles.activities.{$activity->type}"))
                            @include("profiles.activities.{$activity->type}")
                        @endif
                    @endforeach
                @empty
                    <p>There is no activity for this user yet.</p>
                @endforelse

                {{--{{ $threads->links() }}--}}
            </div>
            <!-- /.col-md-8 col-md-offset-2 -->
        </div>
        <!-- /.row -->

    </div>
    <!-- /.container -->
@endsection