@extends('rt.layouts.app')

@section('title', 'Dashboard')

@section('content')

    <!-- Main Content -->
    <div id="content">

        {{-- top bar --}}
         @include('rt.layouts.topbar')

        {{-- top bar end --}}

        <!-- Begin Page Content -->
        <div class="container-fluid">

            {{-- card --}}

            @include('rt.layouts.card')

            {{-- card end --}}
        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->


@endsection
