$(document).ready(function(){


        $('.datepicker').datepicker({
            maxViewMode: 0,
            daysMin:["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
            todayBtn: true,
            language: "fr",
            daysOfWeekDisabled: "1,2",
            daysOfWeekHighlighted: "1,3,4,5,6",
            datesDisabled: ['01/11/2017']
        });



    } );
