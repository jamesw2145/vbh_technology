@extends('layouts.app')

@section('content')
<div class="container mb-5">
    <div class="row justify-content-center my-5">
        <div class="col-md-3">
            <img class="img-fluid" src="{{ asset('img/Logo.png') }}" alt="Logo">
        </div>
    </div>
    <form method="post" id="mainForm">
        @csrf
        <input type="hidden" name="entry_id" id="entry_id">
        <input type="hidden" name="action_type" id="action_type">
        <input type="hidden" name="qty_produced" id="qty_produced">
        <div class="row mb-2">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Product Information</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="production_date">Date</label>
                                    <input type="hidden" name="production_date" id="hidden_prod_date">
                                    <input class="form-control" id="production_date" disabled>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="doc_no">Prod Order#/PT#</label>
                                    <input maxlength="10" class="form-control" id="doc_no" name="doc_no" required>
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
                <button class="btn btn-light border-dark" id="inspect_similar" tabindex="-1">Inspect Similar Part</button>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Completed Assembly</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item_id">Assembly Part #</label>
                                    <input maxlength="40" class="form-control" id="item_id" name="item_id" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hose_item_id">Hose</label>
                                    <input maxlength="40" class="form-control" id="hose_item_id" name="hose_item_id">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="measure_uom">Units</label>
                                    <select class="form-control" id="measure_uom" name="measure_uom">
                                        <option>inches</option>
                                        <option>mm</option>
                                        <option>feet</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="hose_date_code">Date Code</label>
                                    <input maxlength="15" class="form-control" id="hose_date_code" name="hose_date_code" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9 col-lg-10">
                <div class="card mb-2">
                    <div class="card-header">Inspection</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="fitting_1_item_id_1">Fitting #1</label>
                                    <input maxlength="40" class="form-control" id="fitting_1_item_id_1" name="inspection[0][fitting_1_item_id]">
                                </div>
                                <div class="form-group">
                                    <label for="fitting_1_crimp_od_1">Crimp OD #1</label>
                                    <input maxlength="10" class="form-control" id="fitting_1_crimp_od_1" name="inspection[0][fitting_1_crimp_od]" placeholder="0.000000" step="0.0001">
                                </div>
                                <div class="form-group">
                                    <label for="fitting_1_crimp_len_1">Crimp Length #1</label>
                                    <input maxlength="10" class="form-control" id="fitting_1_crimp_len_1" name="inspection[0][fitting_1_crimp_len]" step="0.0001">
                                </div>
                            </div>
                            <div class="divider"></div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="fitting_2_item_id_1">Fitting #2</label>
                                    <input maxlength="40" class="form-control" id="fitting_2_item_id_1" name="inspection[0][fitting_2_item_id]">
                                </div>
                                <div class="form-group">
                                    <label for="fitting_2_crimp_od_1">Crimp OD #2</label>
                                    <input maxlength="10" class="form-control" id="fitting_2_crimp_od_1" name="inspection[0][fitting_2_crimp_od]" placeholder="0.000000" step="0.0001">
                                </div>
                                <div class="form-group">
                                    <label for="fitting_2_crimp_len_1">Crimp Length #2</label>
                                    <input maxlength="10" class="form-control" id="fitting_2_crimp_len_1" name="inspection[0][fitting_2_crimp_len]" step="0.0001">
                                </div>
                            </div>
                            <div class="col-md-2 form-row align-items-center">
                                <div class="form-group w-100">
                                    <label for="hose_measured_len_1">Measured Length</label>
                                    <input maxlength="10" class="form-control" id="hose_measured_len_1" name="inspection[0][hose_measured_len]" placeholder="0.000000" step="0.0001" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">Inspection</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="fitting_1_item_id_2">Fitting #1</label>
                                    <input maxlength="40" class="form-control" id="fitting_1_item_id_2" name="inspection[1][fitting_1_item_id]">
                                </div>
                                <div class="form-group">
                                    <label for="fitting_1_crimp_od_2">Crimp OD #1</label>
                                    <input maxlength="10" class="form-control" id="fitting_1_crimp_od_2" name="inspection[1][fitting_1_crimp_od]" placeholder="0.000000" step="0.0001">
                                </div>
                                <div class="form-group">
                                    <label for="fitting_1_crimp_len_2">Crimp Length #1</label>
                                    <input maxlength="10" class="form-control" id="fitting_1_crimp_len_2" name="inspection[1][fitting_1_crimp_len]" step="0.0001">
                                </div>
                            </div>
                            <div class="divider"></div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="fitting_2_item_id_2">Fitting #2</label>
                                    <input maxlength="40" class="form-control" id="fitting_2_item_id_2" name="inspection[1][fitting_2_item_id]">
                                </div>
                                <div class="form-group">
                                    <label for="fitting_2_crimp_od_2">Crimp OD #2</label>
                                    <input maxlength="10" class="form-control" id="fitting_2_crimp_od_2" name="inspection[1][fitting_2_crimp_od]" placeholder="0.000000" step="0.0001">
                                </div>
                                <div class="form-group">
                                    <label for="fitting_2_crimp_len_2">Crimp Length #2</label>
                                    <input maxlength="10" class="form-control" id="fitting_2_crimp_len_2" name="inspection[1][fitting_2_crimp_len]" step="0.0001">
                                </div>
                            </div>
                            <div class="col-md-2 form-row align-items-center position-relative">
                                <div class="form-group measured-len-group d-flex flex-column align-items-center">
                                    <label for="hose_measured_len_2">Measured Length</label>
                                    <input maxlength="10" class="form-control" id="hose_measured_len_2" name="inspection[1][hose_measured_len]" placeholder="0.000000" step="0.0001">
                                    <button class="btn btn-light border-dark btn-next-measurement" id="next_measurement">Next Measurement</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn-part-complete pt-1">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#additionalFields" id="part_complete">Part Complete</button>
                    <button class="d-none" id="hidden-btn">Part Complete</button>
                </div>
            </div>
            <div class="col-md-3 col-lg-2">
                <div id="accordion" class="mb-2">
                    <div class="card h-100">
                        <div class="card-header btn-link" data-toggle="collapse" data-target="#conversionCardBody" aria-expanded="true" aria-controls="conversionCardBody">Conversions</div>

                        <div class="card-body py-2 collapse show" id="conversionCardBody">
                            <div class="wrapper">
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
                </div>
                <div class="form-group">
                    <label for="comment">Comment</label>
                    <input maxlength="40" class="form-control" id="comment" name="comment">
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
                    <input maxlength="6" class="form-control" id="qty_produced_vi">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-qty-modal">Submit</button>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
    function isNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
    }
    $(document).ready(function(){
        var hiddenProdDate = $('#hidden_prod_date').val();
        $('#production_date').datepicker('setDate', new Date());
        $('#hidden_prod_date').val((new Date()).toUTCString());

        var doc_no = '';
        var inputValue = '';
        var isFirstKey = false;
        var length = 0;

        $('#mainForm input').on('focusout', function(){
            if(!$(this).val())
                $(this).val(inputValue);
            $(this).off('keydown');
            $(this).off('keypress');
        });

        $('#mainForm input').on('focus', function() {
            $(this).select();
            isFirstKey = true;
            // length = parseInt($(this).attr('maxlength'));
            $(this).on('keydown', function(){
                if(isFirstKey){
                    inputValue = $(this).val();
                    $(this).val('');
                }
                isFirstKey = false;
            });
              
            // $(this).keypress(function(e) {
            //     const strVal = $(this).val();
            //     if((length > 0 && strVal.length >= length))
            //         e.preventDefault();
            // });
        });

        $('#doc_no').focus();

        $('#inspect_similar').on('click', function(){
            $('#action_type').val('inspect_similar');
        });

        $('#next_measurement').on('click', function(){
            $('#action_type').val('next_measurement');
        });

        $('#part_complete').on('click', function(){
            event.preventDefault();
        });

        $('#btn-qty-modal').on('click', function(){
            $('#action_type').val('part_complete');
            const qty_produced = $('#qty_produced_vi').val();
            if(qty_produced) {
                $('#qty_produced').val(qty_produced);
                $('#additionalFields').modal('hide');
                setTimeout(function(){ $('#hidden-btn').trigger('click'); }, 500);                
            } else {
                alert(' Qty Produced field is invalid!');
            }
        });

        $('#mainForm').on('submit', function() {
            event.preventDefault();

            const formData =$(this).serialize();
            $.ajax({
                url: '/api/form-submit',
                method: 'POST',
                data: formData,
                success: function(response) {
                    $('#entry_id').val(response.entry_id);
                    if(response.action_type == 'part_complete') {
                        location.reload();
                    }
                    if(response.action_type == 'next_measurement') {
                        const emFieldIds = [
                            'fitting_1_crimp_od_1',
                            'fitting_1_crimp_len_1',
                            'fitting_1_crimp_od_2',
                            'fitting_1_crimp_len_2',
                            'hose_measured_len_1',
                            'fitting_2_crimp_od_1',
                            'fitting_2_crimp_len_1',
                            'fitting_2_crimp_od_2',
                            'fitting_2_crimp_len_2',
                            'hose_measured_len_2',
                        ];

                        for (let index = 0; index < emFieldIds.length; index++) {
                            const id = emFieldIds[index];
                            $('#'+id).val('');
                        }

                        $('#fitting_1_crimp_od_1').focus();

                        $('#hose_measured_len_1').removeAttr('required');
                    }
                    if(response.action_type == 'inspect_similar') {
                        const emFieldIds = [
                            'entry_id',
                            'qty_produced',
                            'qty_produced_vi',
                            'comment',
                            'fitting_1_crimp_od_1',
                            'fitting_1_crimp_len_1',
                            'fitting_1_crimp_od_2',
                            'fitting_1_crimp_len_2',
                            'hose_measured_len_1',
                            'fitting_2_crimp_od_1',
                            'fitting_2_crimp_len_1',
                            'fitting_2_crimp_od_2',
                            'fitting_2_crimp_len_2',
                            'hose_measured_len_2',
                        ];

                        $('#measure_type').val('OAL');
                        $('#measure_uom').val('inches');

                        for (let index = 0; index < emFieldIds.length; index++) {
                            const id = emFieldIds[index];
                            $('#'+id).val('');
                        }

                        $('#doc_no').focus();
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });
    });
</script>
@endsection