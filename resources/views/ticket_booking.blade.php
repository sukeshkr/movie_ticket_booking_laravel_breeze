<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Seat Reservation with PHP & jQuery</title>
<script src="{{asset('assets/jquery.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/ajax.js')}}" type="text/javascript"></script>
<link type="text/css" rel="stylesheet" href="{{asset('assets/style.css')}}" />
</head>

<body>
    <form id="form1" runat="server" method="POST">
      <h2 style="font-size:1.2em;"> Choose Your seats by clicking the below Chair:</h2>
       <div id="holder">
		<ul  id="place">
        </ul>
	   </div>
	 <div style="width:600px;text-align:center;overflow:auto">
    	<ul id="seatDescription">
        <li style="background:url('{{asset('assets/images/available_seat_img.gif')}}') no-repeat scroll 0 0 transparent;">Available Seat</li>
        <li style="background:url('{{asset('assets/images/booked_seat_img.gif')}}') no-repeat scroll 0 0 transparent;">Booked Seat</li>
        <li style="background:url('{{asset('assets/images/selected_seat_img.gif')}}') no-repeat scroll 0 0 transparent;">Selected Seat</li>
    	</ul>
    </div>
	<div style="width:580px;text-align:left;margin:5px">
		<input type="button" id="btnShowNew" class="btn btn-primary" value="Book Now" />
        <input type="button" id="btnShow" value="Show All" />
    </div>
    <div id="sSeats"></div>
    <div id="print-error"></div>
    <div id="sucess-Booking"></div>
    </form>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <script>
$(function() {

    $.ajaxSetup({

        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        }

    });

    var settings = {
        rows: 6,
        cols: 24,
        rowCssPrefix: 'row-',
        colCssPrefix: 'col-',
        seatWidth: 55,
        seatHeight: 55,
        seatCss: 'seat',
        selectedSeatCss: 'selectedSeat',
        selectingSeatCss: 'selectingSeat'
    };

    var init = function(reservedSeat) {
        var str = [],
            seatNo, className;
        for (i = 0; i < settings.rows; i++) {
            for (j = 0; j < settings.cols; j++) {
                seatNo = (i + j * settings.rows + 1);
                className = settings.seatCss + ' ' + settings.rowCssPrefix + i.toString() + ' ' + settings.colCssPrefix + j.toString();
                if ($.isArray(reservedSeat) && $.inArray(seatNo, reservedSeat) != -1) {
                    className += ' ' + settings.selectedSeatCss;
                }
                str.push('<li class="' + className + '"' +
                    'style="top:' + (i * settings.seatHeight).toString() + 'px;left:' + (j * settings.seatWidth).toString() + 'px">' +
                    '<a title="' + seatNo + '">' + seatNo + '</a>' +
                    '</li>');
            }
        }
        $('#place').html(str.join(''));
    };

    //case I: Show from starting
    //init();

    //Case II: If already booked
    var bookedSeats = [5];
    init(bookedSeats);


    $('.' + settings.seatCss).click(function() {
        if ($(this).hasClass(settings.selectedSeatCss)) {
            alert('This seat is already reserved');
        } else {
            $(this).toggleClass(settings.selectingSeatCss);
        }
    });

    $('#btnShow').click(function() {
        var str = [];
        $.each($('#place li.' + settings.selectedSeatCss + ' a, #place li.' + settings.selectingSeatCss + ' a'), function(index, value) {
            str.push($(this).attr('title'));
        });
        alert(str.join(','));
    })

    $('#btnShowNew').click(function() {
        var str = [],
            item;
        $.each($('#place li.' + settings.selectingSeatCss + ' a'), function(index, value) {
            item = $(this).attr('title');
            str.push(item);
        });
        if($.isEmptyObject(str)) {

            $('#print-error').show();

        }
        else {
            $('#print-error').hide();

            $('#sSeats').html('<div class="alert alert-info btn-xs"> Your selected seats are: ' + str.join(',') + "</div>");
        // alert(str.join(','));
        }

        $.ajax({
            type: 'get',
            url: "{{route('booking')}}",
            data: {
                selectedSeats: str.join(',')
            },
            success: function(data) {

                if($.isEmptyObject(data.error)) {

                    $('#sucess-Booking').html("<div class='alert alert-success btn-xs'>"+ data.success+"</div>");
                }
                else {

                    $('#print-error').html("<div class='alert alert-danger btn-xs'>"+ data.error+"</div>");

                }
            }
        })

    })
});
    </script>

</body>
</html>
