

		<!-- JQuery min js -->
		<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>

		<!-- Bootstrap Bundle js -->
		<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

		<!-- Ionicons js -->
		<script src="{{asset('assets/plugins/ionicons/ionicons.js')}}"></script>

		<!-- Internal Select2 js-->
		<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

		<!-- Moment js -->
		<script src="{{asset('assets/plugins/moment/moment.js')}}"></script>

		<!--Internal Sparkline js -->
		<script src="{{asset('assets/plugins/jquery-sparkline/jquery.sparkline.min.js')}}"></script>

		<!-- Moment js -->
		<script src="{{asset('assets/plugins/raphael/raphael.min.js')}}"></script>

		<!-- Internal Piety js -->
		<script src="{{asset('assets/plugins/peity/jquery.peity.min.js')}}"></script>

		<!--Internal  Flot js-->
		{{-- <script src="{{asset('assets/plugins/jquery.flot/jquery.flot.js')}}"></script>
		<script src="{{asset('assets/plugins/jquery.flot/jquery.flot.pie.js')}}"></script>
		<script src="{{asset('assets/plugins/jquery.flot/jquery.flot.resize.js')}}"></script>
		<script src="{{asset('assets/plugins/jquery.flot/jquery.flot.categories.js')}}"></script>
		<script src="{{asset('assets/js/dashboard.sampledata.js')}}"></script>
		<script src="{{asset('assets/js/chart.flot.sampledata.js')}}"></script> --}}

		<!-- Sticky js -->
		<script src="{{asset('assets/js/sticky.js')}}"></script>

		<!-- Rating js-->
		<script src="{{asset('assets/plugins/rating/jquery.rating-stars.js')}}"></script>
		<script src="{{asset('assets/plugins/rating/jquery.barrating.js')}}"></script>

		<!-- P-scroll js -->
		<script src="{{asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
		<script src="{{asset('assets/plugins/perfect-scrollbar/p-scroll.js')}}"></script>

		<!-- Horizontalmenu js-->
		<script src="{{asset('assets/plugins/sidebar/sidebar.js')}}"></script>
		<script src="{{asset('assets/plugins/sidebar/sidebar-custom.js')}}"></script>

		<!-- Eva-icons js -->
		<script src="{{asset('assets/js/eva-icons.min.js')}}"></script>


		<!-- Horizontalmenu js-->
		<script src="{{asset('assets/plugins/horizontal-menu/horizontal-menu-2/horizontal-menu.js')}}"></script>

		<!-- Internal Map -->
		<script src="{{asset('assets/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
		<script src="{{asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>

		<!-- Internal Chart js -->
		{{-- <script src="{{asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script> --}}

		<!--Internal  index js -->
		<script src="{{asset('assets/js/index-dark.js')}}"></script>
		<script src="{{asset('assets/js/jquery.vmap.sampledata.js')}}"></script>

		<!-- custom js -->
		<script src="{{asset('assets/js/custom.js')}}"></script>
		<script src="{{asset('assets/js/jquery.vmap.sampledata.js')}}"></script>

		<!--Internal  Morris js -->
		<script src="{{asset('assets/plugins/raphael/raphael.min.js')}}"></script>
		<script src="{{asset('assets/plugins/morris.js/morris.min.js')}}"></script>

		<!--Internal Chart Morris js -->
		<script src="{{asset('assets/js/chart.morris.js')}}"></script>

		<!--Internal  Datepicker js -->
		<script src="{{ asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>

		<!--Internal  jquery-simple-datetimepicker js -->
		<script src="{{ asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js')}}"></script>

		<!-- Ionicons js -->
		<script src="{{ asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js')}}"></script>

		<!-- Internal Data tables -->
		<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
		<script src="{{ asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
		<script src="{{ asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
		<script src="{{ asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
		<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
		<script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
		<script src="{{ asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
		<script src="{{ asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
		<script src="{{ asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
		<script src="{{ asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
		<script src="{{ asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
		<script src="{{ asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
		<script src="{{ asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
		<script src="{{ asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
		<script src="{{ asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
		<script src="{{ asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
		<script src="{{ asset('assets/js/table-data.js') }}"></script>
		<!--Internal  Fullcalendar js -->

		<script src="{{ asset('assets/plugins/fullcalendar/fullcalendar.min.js') }}"></script>
		<!--Internal App calendar js -->
		<script src="{{ asset('assets/js/app-calendar-events.js') }}"></script>
		<script src="{{ asset('assets/js/app-calendar.js') }}"></script>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
		<!-- Internal Chart flot js -->
		<script src="{{ asset('assets/js/chart.flot.js') }}"></script>
		<script type="text/javascript">
		var table = $('#example').DataTable({
				lengthChange: true,
				buttons: [ 'copy', 'excel', 'pdf', 'colvis' ],
				responsive: true,
				language: {
					searchPlaceholder: 'Search...',
					sSearch: '',
					lengthMenu: '_MENU_ ',
				},
				//scrollY: '50vh',
				"scrollX": true,
				//scrollCollapse: true,
				// paging:  false,
				createdRow: function( row, data, dataIndex, cells){
					if( data[2] ==  'A'){
						$(cells[3]).css('background-color', data.status_color)
					}
				}
				
			});
			table.buttons().container().appendTo( '#example_wrapper .col-md-6:eq(0)' );
		</script>
		<script>
			$(document).ready(function() {
				clockUpdate();
				setInterval(clockUpdate, 1000);
				$('#datetimepicker').datepicker();
				$('.date').datepicker({
				multidate: true,
					format: 'dd-mm-yyyy'
				});
			})

			function clockUpdate() {
			var date = new Date();
			$('.digital-clock').css({'color': '#fff', 'text-shadow': '0 0 6px black'});
			function addZero(x) {
				if (x < 10) {
				return x = '0' + x;
				} else {
				return x;
				}
			}

			function twelveHour(x) {
				if (x > 12) {
				return x = x - 12;
				} else if (x == 0) {
				return x = 12;
				} else {
				return x;
				}
			}

			if(date.getDay() == 0){
				dayname = "Sunday";
			}else if(date.getDay() == 1){
				dayname = "Monday";
			}else if(date.getDay() == 2){
				dayname = "Tuesday";
			}else if(date.getDay() == 3){
				dayname = "Wednesday";
			}else if(date.getDay() == 4){
				dayname = "Thursday";
			}else if(date.getDay() == 5){
				dayname = "Friday";
			}else if(date.getDay() == 6){
				dayname = "Saturday";
			}else{
				dayname = "";
			}
			var h = addZero(date.getHours());
			var m = addZero(date.getMinutes());
			var s = addZero(date.getSeconds());

			var monthNames = ["January", "February", "March", "April", "May", "June",
			"July", "August", "September", "October", "November", "December"];
			var month = monthNames[date.getMonth()];
			var day = String(date.getDate()).padStart(2, '0');
			var year = date.getFullYear();
			var output = day + ' ' + month + ' ' + year;
			document.getElementById("clock").innerHTML = dayname+', '+output+' <span class="badge badge-light">'+h+'</span> : <span class="badge badge-light">'+m+'</span> : <span class="badge badge-light">'+s+'</span>';
			//$('.digital-clock').innerHTML = '<button type="button" class="btn btn-danger mt-1 mb-1"> <span class="badge badge-light badge-pill">'+h+'</span><span class="badge badge-light badge-pill">'+m+'</span><span class="badge badge-light badge-pill">'+s+'</span></button>'
			//$('.digital-clock').text(h + ':' + m + ':' + s)
			}
        </script>
		<script type="text/javascript">
		jQuery(document).ready(function($){
			// $('#dp3').datepicker().on('changeDate', function (ev) {
			// 	$('#date-daily').change();
			// });
			// $('#date-daily').val('0000-00-00');
			$('#datetimepicker').change(function () {
				// $('#stdout').append($('#date-daily').val() + '\n');
				var value = $('#datetimepicker').val()
				window.location.replace("{{ url ('/home') }}?datefilter="+value);
				
				console.log(value);
			});
			$('#datetimepicker2').change(function () {
				// $('#stdout').append($('#date-daily').val() + '\n');
				var value = $('#datetimepicker2').val()
				window.location.replace("{{ url ('/home') }}?datefilter="+value);
				
				console.log(value);
			});
			
		});
		</script>
		
		