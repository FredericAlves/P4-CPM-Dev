$(document).ready(function(){


        $('.datepicker').datepicker({
            maxViewMode: 0,
            daysMin:["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
            todayBtn: true,
            language: "fr",
            daysOfWeekDisabled: "1,6",
            daysOfWeekHighlighted: "1,3,4,5,6",
            datesDisabled: ['01/11/2017','25/12/2017']
        });



    } );
