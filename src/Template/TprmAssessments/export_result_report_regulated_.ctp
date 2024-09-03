<?php error_reporting(E_NOTICE | E_WARNING | E_PARSE); ?>
<!DOCTYPE HTML>
<html>
	<head>
		<title> Assessment Report : THE CLOUD CISO </title>
		<?= $this->Html->meta('icon') ?>
	</head>
	<body style="background:#c7cbd0;">
		<button type="button" onclick="exportHTML();">Download Word Document</button>
		<div id="report" class="report" style="width:8.27in;background-color:#fff; font-family:arial;margin:auto;box-shadow:0 0 5px 1px #888;padding:0.75in;">
			<div class="reportHeader" style="text-align:center;background:#000080;color:#fff;padding:4px;border:5px solid #111;box-shadow:4px 4px 4px #999;">
				<h2 style="color:#fff;text-align:center;">
					THE CLOUD CISO Risk Assessment
					<br>
					for
					<br>
					<?php echo $company->company_name; ?>
				</h2>
			</div>
			<h4 align="center" style="padding:1in;">
				Approvals
			</h4>
			<p>
				<b>
					<span style="color:#000080;">DRAFT</span>
					Submitted by:
				</b>
				<br>
				<span style="color:#999;">
					&nbsp;&nbsp;&nbsp;&nbsp;&mdash; <?php echo $assessment->user->first_name." ".$assessment->user->last_name; ?>
					<br>
					&nbsp;&nbsp;&nbsp;&nbsp;&mdash; <?php echo $assessment->user->position_title; ?> @ <?php echo $company->company_name; ?>
				</span>
			</p>
			<p align="right">
				<b>Date: <?php echo date('m/d/Y',strtotime($assessment->created)); ?></b>
			</p>
			<p>
				<b>
					Approved &amp; Accepted By:
				</b>
				<br>
				&nbsp;&nbsp;&nbsp;&nbsp;&mdash;
				<span style="color:#999;">
					<?php echo $assessment->assessment_statuses[0]->user->first_name; ?>
					<?php echo $assessment->assessment_statuses[0]->user->last_name; ?>
				</span>
			</p>
			<p align="center">
				<b>Revision History</b>
			</p>
			<table cellspacing="0" border=1 width="100%" style="font-size:12px;">
				<thead>
					<tr>
						<th style="background-color:#000080;color:#fff;font-weight:bold;">Version</th>
						<th style="background-color:#000080;color:#fff;font-weight:bold;">Date</th>
						<th style="background-color:#000080;color:#fff;font-weight:bold;">Description</th>
						<th style="background-color:#000080;color:#fff;font-weight:bold;">Author</th>
					</tr>
				</thead>
				<tbody>
					<?php $sr=1; foreach($assessmentStatuses as $aStatus): ?>
						<tr>
							<td align="center">
								<?php if($aStatus->status!="Completed"): ?>
								.<?php echo $sr++; ?>
								<?php else: ?>
									2
								<?php endif; ?>
							</td>
							<td align="center"><?php echo date("m/d/y",strtotime($aStatus->created)); ?></td>
							<td align="center"><?php echo $aStatus->status; ?></td>
							<td><?php echo $aStatus->user->first_name." ".$aStatus->user->last_name; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<h2 style="page-break-before: always;" align="center">Table of Contents</h2>

			<h2 style="page-break-before: always;">1. Executive Summary</h2>
			<h3>1.1 <u>Conclusions</u></h3>
			<!--conclusion based on regulatory body-->
			<?php $sr=0; foreach($assessment->assessments_regulatory_bodies as $aBody): ?>
			<h4>1.1.<?php echo $sr+=1; ?> Regulatory Body: <?php echo $aBody->regulatory_body->name; ?></h4>
			<p>
				This Analysis evaluated the risks and investigated over
				<?php
					$noOfReqs = 0;
					foreach($aBody->assessment_controls as $ac){
						$noOfReqs+=count($ac->assessment_control_requirements);
					}
					echo $noOfReqs;
				?>

				 specific controls requirements as
				part of <?php echo $company->company_name; ?> control environment.
				The analysis concluded that there is unmitigated risk in the following

				<?php
					$noOfRisksAboveMinor=0;
					foreach($aBody->assessment_risks as $ar){
						if($ar->residual_scale!='Minor'){
							$noOfRisksAboveMinor++;
						}
					}
					echo $noOfRisksAboveMinor;
				?>
				 areas:
			</p>
				<table style="font-size:12px;" cellspacing="0" cellpadding="5" border="1" bordercolor="#222222" width="100%">
					<thead>
						<tr>
							<th style="background-color:#000080;color:#fff;">Risk Domain</th>
							<th style="background-color:#000080;color:#fff;">Scale</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($aBody->assessment_risks as $ar): ?>
							<tr>
								<td><?php echo $ar->risk; ?></td>
								<td><?php echo $ar->residual_scale; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<p>
				The highest risk area(s) were identified as
				<?php
					$rss="";
					foreach($riskScales as $rs){
						if($rs->severity_scale!="Minor"){
							$rss.=$rs->severity_scale.", ";
						}
					}
					echo substr($rss,0,-2);
				?>.
			</p>
			<?php foreach($riskScales as $rs): ?>
				<?php if($rs->severity_scale=="Minor"){continue;} ?>
				<p>
					The expected financial impact of an incident classified
				    as "<?php echo $rs->severity_scale; ?>" would be <?php echo $rs->financial_loss; ?>.
				</p>
			<?php endforeach; ?>
			<p>
				And it could bring about customer dissatisfaction, have a temporary business affect, hold a regulatory impact.
			</p>
			<h5>Control Exceptions Noted</h5>
			<p>
				<?php
					$noOfNonCompliance=0;
					foreach($aBody->assessment_controls as $acs){
						foreach($acs->assessment_control_requirements as $acsr){
							if($acsr->compliance_status=="Partially Compliant" || $acsr->compliance_status=="Non Compliant"){
								$noOfNonCompliance++;
							}
						}
					}
				?>
				The assessment identified <?= $noOfNonCompliance ?> controls requirements in the following Control Domains:
			</p>
			<table style="font-size:12px;" cellspacing="0" cellpadding="5" border="1" bordercolor="#222222" width="100%">
				<thead>
					<tr>
						<th style="background-color:#000080;color:#fff;">Control Area</th>
						<th style="background-color:#000080;color:#fff;">Compliance Status</th>
					</tr>
				</thead>
				<tbody>

					<?php foreach($aBody->assessment_controls as $acs): ?>

						<?php if($acs->compliance_status!="Partially Compliant" && $acs->compliance_status!="Non Compliant"){ continue; } ?>

						<tr>
							<td colspan="2">
								<?php echo $acs->name; ?> (<?php echo $acs->compliance_status; ?>)
							</td>
						</tr>
						<tr>
							<td>
								<?php echo $acs->name; ?> requirements
							</td>
							<td>
								<ul>
									<?php foreach($acs->assessment_control_requirements as $acsr): ?>
										<?php if($acsr->compliance_status!="Partially Compliant" && $acsr->compliance_status!="Non Compliant"){continue;} ?>
										<li>
											<?php echo $acsr->name; ?>
											(<?php echo $acsr->compliance_status; ?>)
										</li>
									<?php endforeach; ?>
								</ul>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php endforeach; //loop of regulatory body ends ?>
			<h3>1.2 <u>Table of Findings</u></h3>
			<p style="color:red;">
				This section need more clarification.
			</p>

			<h3>
				2. Methodology and Objective
			</h3>

			<h4>2.1 <u>Methodology</u></h4>
			<?php $sr=0; foreach($assessment->assessments_regulatory_bodies as $aBody): ?>
				<h5>2.1.<?php echo $sr+=1; ?> Regulatory Body: <?php echo $aBody->regulatory_body->name; ?></h5>
				<p align="justify">
					The methodology for this Assessment includes reliance on the collection of
					information gathered through interviews with <?php echo $company->company_name; ?> and the
					collection of related documentation that codifies the organization
					policies and procedures. A total of <?php echo count($aBody->assessment_risks); ?> risk areas
					were assessed and mapped to <?php echo count($aBody->assessment_controls); ?>, in addition
					to a review of the maturity of these processes using the CObIT
					framework for control maturity.
				</p>
			<?php endforeach; //regu body loop ends ?>
			<h4>2.2 <u>Objective</u></h4>
			<p align="justify">
				The objective of the Assessment is to establish a risk profile for
				<?php echo $company->company_name; ?> that
					identifies and alerts Management to unmitigated risks, so that management can reply
					with appropriate strategies to either remediate control exceptions or implement
					compensating controls to mitigate them.
			</p>

			<h3>
				3. Operational Risks
			</h3>
			<?php $sr=3; foreach($assessment->assessments_regulatory_bodies as $aBody): ?>
				<h4><?php echo $sr+=0.1; ?> Regulatory Body: <?php echo $aBody->regulatory_body->name; ?></h4>
				<p align="justify">
					The Analysis considered logical controls in <?php echo count($aBody->assessment_risks); ?>
					Risk Domains for the <?php echo $company->company_name; ?> environment.
				</p>

				<?php $sra=0; foreach($aBody->assessment_risks as $aRisk): ?>

					<h5><?php echo $sr.".".$sra+=1; ?> <?php echo $aRisk->risk; ?></h5>
					<h5>Overview</h5>
					<p align="justify">
						<?php echo $aRisk->risk_description; ?>
					</p>
					<h5>Inherent Risk</h5>
					<p align="justify">
						Overall Inherent Risk Severity Rating: <?php echo $aRisk->inherent_scale; ?>
					</p>
					<p align="justify">
						The expected financial impact of a <?php echo $aRisk->inherent_scale; ?> incident would be
						<?php $rScale = $riskScales[$aRisk->inherent_scale]; ?>
						"<?php echo $rScale->financial_loss; ?>".
						It could also bring about <?php echo $rScale->business_disruption; ?>,
						<?php echo $rScale->regulatory; ?>  and <?php echo $rScale->headline_risk; ?> .
					</p>
					<h5>Risk Management</h5>
					<p style="color:red;">
						This section need more clarification regarding "Exceptions".
					</p>
					<h5>Residual Risks</h5>
					<p align="justify">
						Overall Residual Risk Severity Rating: <?php echo $aRisk->residual_scale; ?>
					</p>
					<p align="justify">
						<?php $rScale = @$riskScales[$aRisk->residual_scale]; ?>
						The expected financial impact of an incident
						using this classification would be "<?php echo @$rScale->financial_loss; ?>".
						An incident could result in <?php echo @$rScale->business_disruption; ?>
						that could bring about <?php echo @$rScale->customer; ?>
						 or cause even <?php echo @$rScale->headline_risk; ?>.
						It would have <?php echo @$rScale->regulatory; ?>.
					</p>
				<?php endforeach; ?>
			<?php endforeach; //regu body loop ends ?>
			<h3>
				4. Maturity Assessment
			</h3>
			<?php $sr=4; foreach($assessment->assessments_regulatory_bodies as $aBody): ?>
				<h4><?php echo $sr+=0.1; ?> Regulatory Body: <?php echo $aBody->regulatory_body->name; ?></h4>
				<p align="justify">
					The Analysis considered controls in <?php echo count($aBody->assessment_controls); ?>
					Control Domains.  In each, a collection of controls was used to gain an overall
					assessment of the control environment.
				</p>
				<p align="justify">
					As part of this Assessment, <?php echo $company->company_name; ?> rated
					<?php echo count($aBody->assessment_controls); ?> broad, yet discreet, Control Domains.
					The assessment of these Control Domains was based on the design and
					operating effectiveness of each functional control requirement contributed
					to the overall Control Domain, and of the Control Maturity of each Control Domain.
				</p>
				<p align="justify">
					The overall Control Maturity rating is based on six
					factors adopted for use in this analysis, as suggested in CobIT:
				</p>
				<ul>
					<?php foreach($mAttributes as $mAttribute): ?>
						<li>
							<?php echo $mAttribute->name; ?>
						</li>
					<?php endforeach; ?>
				</ul>
				<p align="justify">
					<?php $avg=[]; ?>
					<?php foreach($aBody->assessment_controls as $acs): ?>
						<?php
							$avg[]=$acs['maturity_rating'];
						?>
					<?php endforeach; ?>

					Across all of the Control domains, the average Control
					Maturity was <?php echo round(array_sum($avg)/count($avg),2); ?>.
				</p>

				<?php $sra=0; foreach($aBody->assessment_controls as $aControl): ?>
					<h4><?php echo $sr.".".$sra+=1; ?> <?php echo $aControl->name; ?></h4>
					<p>
						Overall Compliance Status Rating: <?php echo $aControl->maturity_rating; ?>
					</p>
					<p style="font-size:12px;">
						This rating indicates:
					</p>
					<table width="100%" border="1" cellspacing="0" cellpaddi ng="3" style="border:1px solid #333;font-size:11px;border-width:0 0 1px 1px">
						<thead>
							<tr>
								<th style="border:1px solid #333;border-width:1px 1px 0 0 ;color:darkblue;">Control Domain</th>
								<th style="border:1px solid #333;border-width:1px 1px 0 0 ;color:darkblue;">Maturity Rating</th>
								<?php foreach($aControl->assessment_maturity_scores as $amScore): ?>
									<th style="border:1px solid #333;border-width:1px 1px 0 0 ;color:darkblue;">
										<?php echo $amScore->maturity_attribute; ?>
									</th>
								<?php endforeach; ?>
							</tr>
							<tr>
								<th style="border:1px solid #333;border-width:1px 1px 0 0 ;color:#fff;background-color:#4600a5;"><?php echo $aControl->name; ?></th>
								<th style="border:1px solid #333;border-width:1px 1px 0 0 ;color:#111;background:#fff58c;"><?php echo $aControl->maturity_rating; ?></th>
								<?php foreach($aControl->assessment_maturity_scores as $amScore): ?>
									<th style="border:1px solid #333;border-width:1px 1px 0 0 ;color:#111;background:#fff58c;">
										<?php echo $amScore->score." - ".$amScore->maturity_option; ?>
									</th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="border:1px solid #333;border-width:1px 1px 0 0 ;"></td>
								<td style="border:1px solid #333;border-width:1px 1px 0 0 ;"></td>
								<?php foreach($aControl->assessment_maturity_scores as $amScore): ?>
									<td style="border:1px solid #333;border-width:1px 1px 0 0 ;">
										<?php echo $mDescriptions[$amScore->maturity_attribute][$amScore->score]; ?>
									</td>
								<?php endforeach; ?>
							</tr>
						</tbody>
					</table>
				<?php endforeach; ?>
			<?php endforeach; //rbody loop ends ?>
			<h3>
				5. Appendices
			</h3>
			<h4>5.1 Mapping of Risk Families to Control Domains</h4>
			<p align="justify">
				The table below associates Risks with the Controls Domains that affect them.
				"P" indicates that the Control Domain is a Primary control, and
				"S" indicates that the Control Domains is a Secondary control,
				in addressing each risk.
			</p>

			<!--mapping-->
			<?php $a=0;  foreach($rcmappings as $rcmap): ?>
				<?php
					$risks = $rcmap['mappings']['risks'];
					$table = $rcmap['mappings']['table'];
					$residuals = [];
					foreach($rcmap['mappings']['risk_ids'] as $rid){
						$residuals[$rid]['total']=0;
						$residuals[$rid]['count']=0;
					}
				?>
				<h5>5.1.<?php echo $a+=1; ?> Regulatory Body: <?= $rcmap->name ?></h5>

				<table cellspacing="0" cellpadding="2" style="font-size:12px;border:1px solid;border-width:0px 0px 1px 1px;">
				  	<thead>
				  		<tr>
				  			<th style="border:1px solid #333;color:#111;border-width:1px 1px 0 0;" class=""></th>
				  			<th style="border:1px solid #333;color:#111;border-width:1px 1px 0 0;" colspan="<?php echo count($risks); ?>">Risks Areas &rarr;</th>
				  		</tr>
				  		<tr>
				  			<th style="border:1px solid #333;color:#111;border-width:1px 1px 0 0;" class=""> Control Areas &darr;</th>
				  			<?php foreach($risks as $k=>$risk): ?>
				  				<th style="background:#800080;color:#ffffff;border:1px solid #333;border-width:1px 1px 0 0;" class=""><?php echo $risk; ?></th>

				  			<?php endforeach; ?>
				  		</tr>
				  	</thead>
				  	<tbody>

				  		<?php $i=1; foreach($table as $risk_id=>$rows): ?>
					  		<tr>
					  			<td style="background:#800080;color:#ffffff;text-align:left;border:1px solid #333;border-width:1px 1px 0 0;"><?php echo $risk_id; ?></td>

					  			<?php $j=0; foreach($rows as $row): ?>
					  				<td style="<?php echo $row['mapping']==""?'background:#ffffcc;':''; ?>border:1px solid #333;border-width:1px 1px 0 0;">
					  					<?php echo $row['mapping']; ?>
					  					<?php
					  						if($row['mapping']=="P"){
					  							$residuals[$row['assessment_risk']['id']]['total']+=$row['assessment_control']['maturity_rating'];
												$residuals[$row['assessment_risk']['id']]['count']++;
					  						}
					  					?>
					  				</td>
					  			<?php $j++; endforeach; ?>
					  		</tr>
				  		<?php endforeach; ?>
				  		<tr class="">
				  			<td style="border:1px solid #333;border-width:1px 1px 0 0;" class="">

			  				</td>
			  				<?php foreach($residuals as $residual): ?>
			  					<td style="background:#800080;color:#ffffff;text-align:left;border:1px solid #333;border-width:1px 1px 0 0;"><?php echo $residual['count']==0?:round($residual['total']/$residual['count'],2); ?></td>
			  				<?php endforeach; ?>
				  		</tr>
				  	</tbody>
				  </table>

			<?php endforeach; ?>




		<script src="<?php echo $this->request->getAttribute('webroot').'plugins/jquery/jquery.min.js'; ?>"></script>

		<script>
			$(function(){
				//exportHTML();
				//$('#report').html("");
				//window.close();
			});


		    function exportHTML(){
		       var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' "+
		            "xmlns:w='urn:schemas-microsoft-com:office:word' "+
		            "xmlns='http://www.w3.org/TR/REC-html40'>"+
		            "<head><meta charset='utf-8'><title>THE CLOUD CISO: Assessment Report</title></head><body>";
		       var footer = "</body></html>";
		       var sourceHTML = header+document.getElementById("report").innerHTML+footer;

		       var source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
		       var fileDownload = document.createElement("a");
		       document.body.appendChild(fileDownload);
		       fileDownload.href = source;
		       fileDownload.download = '<?php echo $assessment->case_number."_".$assessment->name; ?>.doc';
		       fileDownload.click();
		       document.body.removeChild(fileDownload);
		    }
		</script>
	</body>
</html>