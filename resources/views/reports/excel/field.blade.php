<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<body>
	<table>
		<tr>
			<td style="text-align: left;"><img align="left" @if(file_exists(public_path('logos/'.$field->area->company_id.'.'. $field->area->company->logo_extension ))) src="{{{ public_path('logos/'.$field->area->company_id.'.'. $field->area->company->logo_extension ) }}}" @else src="{{{ public_path('/images/mwp.png') }}}"@endif style="padding-left: 0; margin-left: 0;"/></td>
			<td></td>
			<td></td>
			<td></td>
			<td align="right" valign="middle" style="font-size: 16px; text-align: center;">{{ $field->name }}</td>
			<td></td>
			<td></td>
			<td width="12"></td>
			<td></td>
			<td width="10"></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td width="21"></td>
			<td width="36"></td>
			<td></td>
			<td></td>
		</tr>
		<tr></tr>
		<tr></tr>
		<tr>
			<td align="center" height="40" valign="middle" style="background-color: #51dfef; color: #fff; font-style: italic; font-weight: bold; font-size: 16px; text-decoration: underline; border-top: 4px solid #000; border-bottom: 4px solid #000;">Consumption Tracker</td>
		</tr>
		<tr>
			<td align="left" height="14" style="background-color: #DDDDDD;">Date: {{ $date->format('F Y') }}</td>
		</tr>
		<tr class="headings">
			<td valign="middle" height="50" width="30" style="background-color: #51dfef; color: #fff; font-weight: bold; text-align: center; border-top: 4px solid #000; border-bottom: 4px solid #000; border-right: 2px solid #000;">Location</td>
			<td valign="middle" height="50" style="background-color: #51dfef; color: #fff; font-weight: bold; text-align: center; border-top: 4px solid #000; border-bottom: 4px solid #000; border-right: 2px solid #000; ">Unique Well Id</td>
			<td valign="middle" height="50" style="background-color: #51dfef; color: #fff; font-weight: bold; text-align: center; border-top: 4px solid #000; border-bottom: 4px solid #000; border-right: 2px solid #000; ">Chemical Name</td>
			<td valign="middle" height="50" style="background-color: #51dfef; color: #fff; font-weight: bold; text-align: center; border-top: 4px solid #000; border-bottom: 4px solid #000; border-right: 2px solid #000;">Program</td>
			<td valign="middle" height="50" width="10" style="background-color: #51dfef; color: #fff; font-weight: bold; text-align: center; border-top: 4px solid #000; border-bottom: 4px solid #000; border-right: 2px solid #000; wrap-text: true;">Days<br />In<br />Period</td>
			<td align="center" valign="middle" height="50" style="background-color: #51dfef; color: #fff; font-weight: bold; text-align: center; border-top: 4px solid #000; border-bottom: 4px solid #000; border-right: 2px solid #000; wrap-text: true;">Monthly<br />Open<br />Inventory</td>
			<td align="center" valign="middle" height="50" style="background-color: #51dfef; color: #fff; font-weight: bold; text-align: center; border-top: 4px solid #000; border-bottom: 4px solid #000; border-right: 2px solid #000; wrap-text: true;">Monthly<br />Deliveries</td>
			<td align="center" valign="middle" height="50" style="background-color: #51dfef; color: #fff; font-weight: bold; text-align: center; border-top: 4px solid #000; border-bottom: 4px solid #000; border-right: 2px solid #000; wrap-text: true;">Monthly<br />Closing<br />Inventory</td>
			<td align="center" valign="middle" height="50" style="background-color: #51dfef; color: #fff; font-weight: bold; text-align: center; border-top: 4px solid #000; border-bottom: 4px solid #000; border-right: 2px solid #000; wrap-text: true;">Monthly<br />Usage(L)</td>
			<td align="center" valign="middle" height="50" style="background-color: #51dfef; color: #fff; font-weight: bold; text-align: center; border-top: 4px solid #000; border-bottom: 4px solid #000; border-right: 2px solid #000; wrap-text: true;">Actual<br />Rate(L/Day)</td>
			<td align="center" valign="middle" height="50" style="background-color: #51dfef; color: #fff; font-weight: bold; text-align: center; border-top: 4px solid #000; border-bottom: 4px solid #000; border-right: 2px solid #000; wrap-text: true;">Target<br />Rate(L/Day)</td>
			<td align="center" valign="middle" height="50" width="10" style="background-color: #51dfef; color: #fff; font-weight: bold; text-align: center; border-top: 4px solid #000; border-bottom: 4px solid #000; border-right: 2px solid #000;">Variance</td>
			<td align="center" valign="middle" height="50" width="10" style="background-color: #51dfef; color: #fff; font-weight: bold; text-align: center; border-top: 4px solid #000; border-bottom: 4px solid #000; border-right: 2px solid #000;">Oil Prod<br />(m3/d)</td>
			<td align="center" valign="middle" height="50" width="10" style="background-color: #51dfef; color: #fff; font-weight: bold; text-align: center; border-top: 4px solid #000; border-bottom: 4px solid #000; border-right: 2px solid #000;">Water Prod<br />(m3/d)</td>
			<td align="center" valign="middle" height="50" width="10" style="background-color: #51dfef; color: #fff; font-weight: bold; text-align: center; border-top: 4px solid #000; border-bottom: 4px solid #000; border-right: 2px solid #000;">Actual PPM</td>
			<td align="center" valign="middle" height="50" width="10" style="background-color: #51dfef; color: #fff; font-weight: bold; text-align: center; border-top: 4px solid #000; border-bottom: 4px solid #000; border-right: 2px solid #000; wrap-text: true;">Cost<br />Per<br />Liter</td>
			<td align="center" valign="middle" height="50" width="15" style="background-color: #51dfef; color: #fff; font-weight: bold; text-align: center; border-top: 4px solid #000; border-bottom: 4px solid #000; wrap-text: true; border-right: 2px solid #000;">Actual Monthly<br />Chemical Cost</td>
			<td align="center" valign="middle" height="50" width="15" style="background-color: #51dfef; color: #fff; font-weight: bold; text-align: center; border-top: 4px solid #000; border-bottom: 4px solid #000; wrap-text: true; border-right: 2px solid #000;">Target Monthly<br />Chemical Cost</td>
			<td align="center" valign="middle" height="50" style="background-color: #51dfef; color: #fff; font-weight: bold; text-align: center; border-top: 4px solid #000; border-bottom: 4px solid #000; wrap-text: true; border-right: 2px solid #000;">Monthly Cost<br />Variance</td>
			<td valign="middle" height="50" width="50" style="background-color: #51dfef; color: #fff; font-weight: bold; text-align: center; border-top: 4px solid #000; border-bottom: 4px solid #000; wrap-text: true;">Comments</td>
		</tr>
		<?php $counter = 7; ?>
		@foreach($continuousInjections as $injection)
		<tr>
			<td>{{ $injection->location->name }}</td>
			<td>{{ $injection->location->description }}</td>
			<td align="center">{{ $injection->name }}</td>
			<td align="center">{{ $injection->chemical_type }}</td>
			<td>{{ $injection->days_in_month  }}</td>
			<td>{{ $injection->chemical_start }}</td>
			<td>{{ $injection->chemical_delivered }}</td>
			<td>{{ $injection->chemical_end }}</td>
			<td>{{ $injection->chemicalUsed() }}</td>
			<td>{{ $injection->usageRate() }}</td>
			<td>{{ $injection->vendor_target }}</td>
			<td>{{ $injection->usageRate() - $injection->vendor_target }}</td>
			<td>{{ round($injection->production->avg_oil, 1) }}</td>
			<td>{{ round($injection->production->avg_water, 1) }}</td>
			<td>{{ $injection->actualPPM() }}</td>
			<td>{{ $injection->unit_cost * (0.01) }}</td>
			<td>{{ $injection->totalMonthlyCost() }}</td>
			<td>{{ $injection->targetMonthlyChemicalCost() }}</td>
			<td>{{ $injection->monthlyCostVariance() }}</td>
			<td>{{ $injection->comments }}</td>
			<?php $counter++ ?>
		</tr>
		@endforeach
		<tr></tr>
		@if(! $continuousInjections->isEmpty())
		<tr></tr>
		<tr>
			<td>Totals:</td><td></td><td></td><td></td><td></td><td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>=SUM(P7:P{{ $counter }})</td>
			<td>=SUM(Q7:Q{{ $counter }})</td>
			<td>=SUM(R7:R{{ $counter }})</td>
			<td>=SUM(S7:S{{ $counter }})</td>
		</tr>
		<tr></tr>
		<tr>
			<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
			<td>Cost Below Target:</td><td>=IF(P{{ $counter + 2 }}>Q{{ $counter + 2 }},"-","")&amp;TEXT(ABS(R{{ $counter + 2 }})/Q{{ $counter + 2 }}, "0.00%")</td>
		</tr>
		@else
			<?php $counter -= 4; ?>
		@endif
		@if(! $batchInjections->isEmpty())
		<tr></tr>
		<tr><td style="font-weight: bold;">Batch Injections:</td></tr>
		<tr></tr>
		<?php
			$start = $counter + 7;
			$counter = $start;
		?>
		@foreach($batchInjections as $injection)
		<tr>
			<td>{{ $injection->location->name }}</td>
			<td>{{ $injection->location->description }}</td>
			<td align="center">{{ $injection->name }}</td>
			<td align="center">{{ $injection->chemical_type }}</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>{{ $injection->batch_size }}</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>{{ $injection->unit_cost * (0.01) }}</td>
			<td>{{ $injection->batch_size * $injection->unit_cost * (0.01) }}</td>
			<td></td>
			<td></td>
			<?php $counter++ ?>
		</tr>
		@endforeach
		<tr></tr>
		<tr></tr>
		<tr>
			<td>Totals:</td><td></td><td></td><td></td><td></td><td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>=SUM(Q{{ $start }}:Q{{ $counter }})</td>
		</tr>
		@endif
	</table>
	</body>
</html>