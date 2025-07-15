@extends('rw.layouts.app')

@section('title', 'Dashboard')

@section('content')

    <!-- Main Content -->
    <div id="content">

        {{-- top bar --}}
         @include('rw.layouts.topbar')

        {{-- top bar end --}}

        <!-- Begin Page Content -->
        <div class="container-fluid">

            {{-- card --}}

            @include('rw.layouts.card')

            {{-- card end --}}

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->


@endsection
