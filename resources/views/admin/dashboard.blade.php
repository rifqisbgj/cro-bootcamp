@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
@endsection
@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
@endsection

@section('title')
    My Dashboard
@endsection

@section('content')
    <section class="dashboard my-5">
        <div class="container">
            @include('components.alert')
            <table id="example" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Camp</th>
                        <th>Price</th>
                        <th>Register Date</th>
                        <th>Paid Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ck as $co)
                        <tr>
                            <td>{{ $co->user->name }}</td>
                            <td>{{ $co->camp->title }}</td>
                            <td>{{ $co->camp->price }}</td>
                            <td>{{ $co->created_at->format('M d Y') }}</td>
                            <td>
                                @if ($co->payment_status == 'waiting')
                                    <span class="badge bg-warning">Waiting</span>
                                @elseif($co->payment_status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($co->payment_status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-danger">Failed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No Data</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th>User</th>
                        <th>Camp</th>
                        <th>Price</th>
                        <th>Register Date</th>
                        <th>Paid Status</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>
@endsection
