@extends('warga.layouts.app')

@section('title', 'Dashboard')

@section('konten')

    <!-- Main Content -->
    <div id="content">

        {{-- top bar --}}
         @include('warga.layouts.topbar')

        {{-- top bar end --}}

        <!-- Begin Page Content -->
        <div class="container-fluid">

            {{-- card --}}

            @include('warga.layouts.card')

            {{-- card end --}}
        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->


@endsection
