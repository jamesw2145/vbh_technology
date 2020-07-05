@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center my-3">
        <div class="col-md-3">
            <img class="img-fluid" src="{{ asset('img/Logo.png') }}" alt="Logo">
        </div>
    </div>
    <form method="post" id="mainForm">
        @csrf
        <input type="hidden" name="entry_id" id="entry_id">
        <div class="row mb-3">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Product Information</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="production_date">Date</label>
                                    <input class="form-control" id="production_date" name="production_date" disabled>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="doc_no">Prod Order#/PT#</label>
                                    <input class="form-control" id="doc_no" name="doc_no" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="technician">Technician</label>
                                    <select class="form-control" id="technician" name="technician" required>
                                        <option value="">Select</option>
                                        <option>A. Chappelow</option>
                                        <option>N. Chappelow</option>
                                        <option>L. Collins</option>
                                        <option>T. Gibson</option>
                                        <option>I. Gosmeyer</option>
                                        <option>L. Jasper</option>
                                        <option>E. Kelley</option>
                                        <option>L. Lommel</option>
                                        <option>D. Szempruch</option>
                                        <option>J. Urbanski</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 btn-inspect">
                <button class="btn btn-light border-dark w-100">Inspect Similar Part</button>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Completed Assembly</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="item_id">Assembly Part #</label>
                                    <input class="form-control" id="item_id" name="item_id" required>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="hose_item_id">Hose</label>
                                    <input class="form-control" id="hose_item_id" name="hose_item_id">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="measure_type">Measurement Type</label>
                                    <select class="form-control" id="measure_type" name="measure_type">
                                        <option>OAL</option>
                                        <option>Cut Length</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="measure_uom">Units</label>
                                    <select class="form-control" id="measure_uom" name="measure_uom">
                                        <option>inches</option>
                                        <option>mm</option>
                                        <option>feet</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="hose_date_code">Date Code</label>
                                    <input type="number" class="form-control" id="hose_date_code" name="hose_date_code" placeholder="0" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-9">
                <div class="card mb-3">
                    <div class="card-header">Inspection</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 border-right">
                                <div class="form-group">
                                    <label for="fitting_1_item_id_1">Fitting #1</label>
                                    <input class="form-control" id="fitting_1_item_id_1" name="inspection[0][fitting_1_item_id]">
                                </div>
                                <div class="form-group">
                                    <label for="fitting_1_crimp_od_1">Crimp OD #1</label>
                                    <input type="number" class="form-control" id="fitting_1_crimp_od_1" name="inspection[0][fitting_1_crimp_od]" placeholder="0.0000">
                                </div>
                                <div class="form-group">
                                    <label for="fitting_1_crimp_len_1">Crimp Length #1</label>
                                    <input class="form-control" id="fitting_1_crimp_len_1" name="inspection[0][fitting_1_crimp_len]">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fitting_2_item_id_1">Fitting #2</label>
                                    <input class="form-control" id="fitting_2_item_id_1" name="inspection[0][fitting_2_item_id]">
                                </div>
                                <div class="form-group">
                                    <label for="fitting_2_crimp_od_1">Crimp OD #2</label>
                                    <input type="number" class="form-control" id="fitting_2_crimp_od_1" name="inspection[0][fitting_2_crimp_od]" placeholder="0.0000">
                                </div>
                                <div class="form-group">
                                    <label for="fitting_2_crimp_len_1">Crimp Length #2</label>
                                    <input class="form-control" id="fitting_2_crimp_len_1" name="inspection[0][fitting_2_crimp_len]">
                                </div>
                            </div>
                            <div class="col-md-4 form-row align-items-center">
                                <div class="form-group">
                                    <label for="hose_measured_len_1">Measured Length</label>
                                    <input type="number" class="form-control" id="hose_measured_len_1" name="inspection[0][hose_measured_len]" placeholder="0.0000" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">Inspection</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 border-right">
                                <div class="form-group">
                                    <label for="fitting_1_item_id_2">Fitting #1</label>
                                    <input class="form-control" id="fitting_1_item_id_2" name="inspection[1][fitting_1_item_id]">
                                </div>
                                <div class="form-group">
                                    <label for="fitting_1_crimp_od_2">Crimp OD #1</label>
                                    <input type="number" class="form-control" id="fitting_1_crimp_od_2" name="inspection[1][fitting_1_crimp_od]" placeholder="0.0000">
                                </div>
                                <div class="form-group">
                                    <label for="fitting_1_crimp_len_2">Crimp Length #1</label>
                                    <input class="form-control" id="fitting_1_crimp_len_2" name="inspection[1][fitting_1_crimp_len]">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fitting_2_item_id_2">Fitting #2</label>
                                    <input class="form-control" id="fitting_2_item_id_2" name="inspection[1][fitting_2_item_id]">
                                </div>
                                <div class="form-group">
                                    <label for="fitting_2_crimp_od_2">Crimp OD #2</label>
                                    <input type="number" class="form-control" id="fitting_2_crimp_od_2" name="inspection[1][fitting_2_crimp_od]" placeholder="0.0000">
                                </div>
                                <div class="form-group">
                                    <label for="fitting_2_crimp_len_2">Crimp Length #2</label>
                                    <input class="form-control" id="fitting_2_crimp_len_2" name="inspection[1][fitting_2_crimp_len]">
                                </div>
                            </div>
                            <div class="col-md-4 form-row align-items-center">
                                <div class="form-group">
                                    <label for="hose_measured_len_2">Measured Length</label>
                                    <input type="number" class="form-control" id="hose_measured_len_2" name="inspection[1][hose_measured_len]" placeholder="0.0000" required>
                                </div>
                                <button class="btn btn-light border-dark position-absolute btn-next-measurement">Next Measurement</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn-part-complete">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#additionalFields">Part Complete</button>
                </div>
            </div>
            <div class="col-md-3 pb-3">
                <div id="accordion" class="mb-3">
                    <div class="card h-100">
                        <div class="card-header btn btn-link" data-toggle="collapse" data-target="#conversionCardBody" aria-expanded="true" aria-controls="conversionCardBody">Conversions</div>

                        <div class="card-body px-5 py-3 collapse show" id="conversionCardBody">
                            <p>1/16 = .0625</p>
                            <p>1/8 = .125</p>
                            <p>3/16 = .1875</p>
                            <p>1/4 = .25</p>
                            <p>5/16 = .3125</p>
                            <p>3/8 = .375</p>
                            <p>7/16 = .4375</p>
                            <p>1/2 = .5</p>
                            <p>9/16 = .5625</p>
                            <p>5/8 = .625</p>
                            <p>11/16 = .6875</p>
                            <p>3/4 = .75</p>
                            <p>13/16 = .8125</p>
                            <p>7/8 = .875</p>
                            <p>15/16 = .9375</p>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="comment">Comment</label>
                    <input class="form-control" id="comment" name="comment">
                </div>
            </div>
        </div>
    </form>
    <div class="modal fade" id="additionalFields" tabindex="-1" role="dialog" aria-labelledby="additionalFields" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Total Qty Produced</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input class="form-control" id="qty_produced" name="qty_produced">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Submit</button>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
    $(document).ready(function(){
        $('#production_date').datepicker('setDate', new Date());

        $('#mainForm').on('submit', function() {
            event.preventDefault();

            const formData =$(this).serialize();
            $.ajax({
                url: '/api/form-submit',
                method: 'POST',
                data: formData,
                success: function(response) {
                    console.log(response);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });
    });
</script>
@endsection