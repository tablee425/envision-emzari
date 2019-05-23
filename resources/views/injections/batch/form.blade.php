@extends('layouts.main')

@section('content')

<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>Batch Injection</li>
            <li>Details</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-home"></i> 
                Batch Injection 
            <span>>  
                Details
            </span>
        </h1>
    </div>
    <div class="col-lg-10 col-lg-offset-1" style="margin-bottom: 40px;">
        <div class="jarviswidget" id="company-widget" data-widget-editbutton="false" data-widget-custombutton="false">
    <header>
        <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
        <h2>Batch Injection</h2>
    </header>

    <div>
        <div class="jarviswidget-editbox">
            
        </div>

        <div class="widget-body no-padding">
            <form class="smart-form" method="POST" action="{{ action($action) }}" novalidate="novalidate">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $injection->id }}" />
                <input type="hidden" name="location_id" value="{{ $location_id }}" />
                <header>
                    Batch Injection Data for
                </header>
                <fieldset>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Date</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name="date" placeholder="YYYY-MM" value="{{ $injection->date ? date('Y-m', strtotime($injection->date)) : date('Y-m') }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Days In Month</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name="days_in_month" placeholder="30" value="{{ $injection->days_in_month }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Gas Production</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name ="avg_gas" placeholder="Gas Production" value="{{ $production->avg_gas }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Oil Production</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name="avg_oil" placeholder="Oil Production" value="{{ $production->avg_oil }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Water Production</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name="avg_water" placeholder="Water Production" value="{{ $production->avg_water }}">
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-3">
                            <label class="label">Total Production</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input type="text" placeholder="Production" value="{{ $production->totalProduction() }}" disabled>
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">How much delivered?</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input  type="text" name="chemical_delivered" placeholder="Chemical Delivered" value="{{ $injection->chemical_delivered }}">
                            </label>
                        </section>
                        <section class="col col-3">
                        <label class="label">How much at end?</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input  type="text" name="inventory_end" placeholder="Inventory - End" value="{{ $injection->chemical_end }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Chemical</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name="name" placeholder="Chemical Name" value="{{ $injection->name }}">
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Chemical Type</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <label class="select">
                                <select  name="chemical_type" class="form-control">
                                    <option value="demulsifier" @if ($injection->chemical_type == 'demulsifier') selected @endif>Demulsifier</option>    
                                    <option value="corrosion_inhibitor" @if ($injection->chemical_type == 'corrosion_inhibitor') selected @endif>Corrosion Inhibitor</option>
                                    <option value="paraffin_solvent" @if($injection->chemical_type == 'paraffin_solvent') selected @endif>Paraffin Solvent</option> 
                                    <option value="demulsifier_wax" @if($injection->chemical_type == 'demulsifier_wax') selected @endif>Demulsifier/Wax</option>
                                    <option value="biocide" @if($injection->chemical_type == 'biocide') selected @endif>Biocide</option>
                                    <option value="scale_inhibitor" @if($injection->chemical_type == 'scale_inhibitor') selected @endif>Scale Inhibitor</option>
                                    <option value="scale_corrosion_combo" @if($injection->chemical_type == 'scale_corrosion_combo') selected @endif>Scale/Corrosion Combo</option>
                                    <option value="vapour_phase_corrosion_inhibitor" @if($injection->chemical_type == 'vapour_phase_corrosion_inhibitor') selected @endif>Vapour Phase Corrosion Inhibitor</option>
                                    <option value="iron_oxide_dissolver" @if($injection->chemical_type == 'iron_oxide_dissolver') selected @endif>Iron Oxide Dissolver</option> 
                                    <option value="oxygen_scavenger" @if($injection->chemical_type == 'oxygen_scavenger') selected @endif>Oxygen Scavenger</option>
                                    <option value="h2s_scavenger" @if($injection->chemical_type == 'h2s_scavenger') selected @endif>H2S Scavenger</option>
                                    <option value="defoamer" @if($injection->chemical_type == 'defoamer') selected @endif>Defoamer</option> 
                                    <option value="wax_dispersant" @if($injection->chemical_type == 'wax_dispersant') selected @endif>Wax Dispersant</option>
                                    <option value="methanol" @if($injection->chemical_type == 'methanol') selected @endif>Methanol</option>
                                    <option value="ethylene_glycol" @if($injection->chemical_type == 'ethylene_glycol') selected @endif>Ethylene Glycol</option>
                                    <option value="varsol" @if($injection->chemical_type == 'varsol') selected @endif>Varsol</option>
                                    <option value="slugging_demulsifer" @if($injection->chemical_type == 'slugging_demulsifier') selected @endif>Slugging Demulsifier</option>
                                    <option value="iron_control" @if($injection->chemical_type == 'iron_control') selected @endif>Iron Control</option>
                                    <option value="friction_reducer" @if($injection->chemical_type == 'friction_reducer') selected @endif>Friction Reducer</option>
                                    <option value="surfactant" @if($injection->chemical_type == 'surfactant') selected @endif>Surfactant</option>
                                    <option value="foamer" @if($injection->chemical_type == 'foamer') selected @endif>Foamer</option>
                                    <option value="paraffin_inhibitor" @if($injection->chemical_type == 'paraffin_inhibitor') selected @endif>Paraffin Inhibitor</option>
                                </select>
                            </label>
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Current Chemical Usage Rate</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input  type="text" name="usage_rate" placeholder="How much to start?" value="{{ $injection->usageRate() }}">
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Chemical Days Remaining</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input type="text" placeholder="Days Left" value="{{ $injection->daysRemaining() }}" disabled>
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-2">
                        <label class="label">Target Rate</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input  type="text" name="target_rate" placeholder="Target Rate" value="{{ $injection->targetRate() }}">
                            </label>
                        </section>
                        <section class="col col-2">
                        <label class="label">Chemical Vendor Target</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input  type="text" name="vendor_target" placeholder="Vendor Target" value="{{ $injection->vendor_target }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Minimum Rate</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input type="text" name="minimum_rate" placeholder="Minimum Rate" value="{{ $injection->min_rate }}" disabled>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-3">
                            <label class="label">Over/Under</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input type="text" placeholder="Over Under" value="{{ $injection->overUnder() }}" disabled>
                            </label>
                        </section>
                        <section class="col col-3">
                        <label class="label">Unit Cost</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input  type="text" name="unit_cost" placeholder="Unit Cost" value="{{ $injection->unit_cost / 100}}">
                            </label>
                        </section>
                        <section class="col col-3">
                        <label class="label">Monthly Over Injection Cost</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input type="text" placeholder="Inventory - End" value="{{ $injection->chemical_end }}" disabled>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-3">
                        <label class="label">Paraffin Solvent Ratio</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input type="text" value="{{ $injection->paraffinRatio() }}" disabled>
                            </label>
                        </section>
                        <section class="col col-3">
                        <label class="label">Corrosion Inhibitor Ratio</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input type="text" placeholder="Inventory - End" value="{{ $injection->corrosionInhibitorRatio() }}" disabled>
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Vendor Budget</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input type="text" placeholder="Vendor Budger" value="{{ $injection->vendorBudget() }}" disabled>
                            </label>
                        </section>
                        <section class="col col-2">
                        <label class="label">Target Budget</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input type="text" placeholder="Target Budget" value="{{ $injection->targetBudget() }}" disabled>
                            </label>
                        </section>
                        <section class="col col-2">
                        <label class="label">Actual Rate</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input type="text" placeholder="Actual Rate" value="{{ $injection->actualRate() }}" disabled>
                            </label>
                        </section>
                        <section class="col col-2">
                        <label class="label">Total Monthly Cost</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input type="text" placeholder="Total Cost" value="{{ $injection->totalMonthlyCost() }}" disabled>
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-4">
                            <label class="textarea"> <i class="icon-append fa fa-comment"></i>
                                <textarea  rows="5" name="comments" placeholder="Comments">{{ $injection->comments }}</textarea>
                            </label>
                        </section>
                    </div>

                </fieldset>
                
                <footer>
                    <button  type="submit" class="btn btn-primary">
                        {{ $button }}
                    </button>
                </footer>
            </form>
        </div>
    </div>
</div>
    </div>
</div>
@endsection