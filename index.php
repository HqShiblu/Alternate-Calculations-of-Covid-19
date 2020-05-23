<html>	
	<head>
		<title>COVID-19 Test Calculator</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"/>		
		<style>
			table{
				width:100%;
			}
			th{
				font-weight:bold;
			}
		</style>
	</head>
	
	<body>
				
		<br/>
		<br/>
		<br/>
		
		<div id="statistics_holder">
			
		</div>
		
		
		<br/>
		<br/>
		<br/>
		
		<table id="main_table" class="table table-hover table-striped">		
			<?php
				$worldometer_data = file_get_contents('https://www.worldometers.info/coronavirus/');
				$start_content = explode( '<table id="main_table_countries_today" class="table table-bordered table-hover main_table_countries" style="width:100%;margin-top: 0px !important;">' , $worldometer_data);
				$final_content = explode("</table>" , $start_content[1] );
				echo $final_content[0];
			?>
		</table>
		
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
		<script>
			
			$(document).ready(function(){
				
				var total_info = [];
								
				$("#main_table").children("tbody").children("tr").each(function(){					
					var country = $.trim($(this).children("td:eq(1)").text());
					var total_cases = parseInt($.trim($(this).children("td:eq(2)").text()).split(",").join(""));
					var total_tests = parseInt($.trim($(this).children("td:eq(11)").text()).split(",").join(""));
					var tests_per_million = parseInt($.trim($(this).children("td:eq(12)").text()).split(",").join(""));
					
					if(total_cases<total_tests){
						var country_info = {
							country:country,
							total_cases:total_cases,
							total_tests:total_tests,
							tests_per_million:tests_per_million,
							case_ratio:(total_cases/total_tests)*100
						};
						if(total_cases/total_tests*100!=NaN){
							total_info.push(country_info);
						}
					}
				});
				
				total_info.sort((a, b)=>{
					return parseFloat(a.case_ratio) - parseFloat(b.case_ratio);
				});
				
				var computed_html = '<table id="main_table" class="table table-hover table-striped"><thead>'+
				'<tr><th>Country</th><th>Total Cases</th><th>Total Tests</th><th>Cases Per 100 Tests</th><th>Tests Per Case</th><th>Tests Per Million Population</th></tr></thead><tbody>';
				
				for(var info of total_info){					
					computed_html+='<tr><td>'+info['country']+'</td><td>'+info['total_cases']+'</td><td>'+info['total_tests']+'</td><td>'+info['case_ratio']+'</td><td>'+(100/info['case_ratio'])+'</td><td>'+info['tests_per_million']+'</td></tr>';
					
					//console.log(info['country']+" : "+info['case_ratio']+" ("+(100/info['case_ratio'])+")"+"\nTotal Tests : "+info['total_tests']+"\nTotal Cases : "+info['total_cases']+"\nTests per million : "+info['tests_per_million']);
					
					//console.log(info['country']+" : "+(100/info['case_ratio']));
					
					if(info['total_cases']>30000 && info['tests_per_million']<2000){
						console.log(info['country']+" : "+info['case_ratio']+"\nTests per million : "+info['tests_per_million']);
					}
				}				
				computed_html+="</tbody></table>";				
				$("#statistics_holder").html(computed_html);
				
			});
					
		</script>		
	</body>
</html>