$(function ($) {

    // on récupère la date du jour et on la formate pour la comparer à celle du datepicker

    var $today = new Date();

    var $dayToday = $today.getDate();

    if ($dayToday < 10){
        $dayToday = '0'+$today.getDate();
    }

    var $twoDigitMonth = (($today.getMonth().length+1) === 1)? ($today.getMonth()+1) : '0' + ($today.getMonth()+1);

    var $todayFormatted = $dayToday + "-" + $twoDigitMonth + "-" + $today.getFullYear();

    var $hour = $today.getHours();

    var $durationChoice = $('#louvre_ticketingbundle_booking_duration');
    var $reservationDateInput = $('#louvre_ticketingbundle_booking_dateOfVisit');

    var $dateOfVisit = $reservationDateInput.val();

    if (($dateOfVisit == $todayFormatted) && ($hour >= 14)){
        $durationChoice.val('demi-journée');
        rendreDDLReadOnly();
    } else {
        $durationChoice.val('journée');
        rendreDDLEditable();
    }



    $reservationDateInput.change(function () {
        var $dateOfVisit = $reservationDateInput.val();
        if (($dateOfVisit == $todayFormatted) && ($hour >= 14)){
            $durationChoice.val('demi-journée');
            rendreDDLReadOnly();
        } else {
            $durationChoice.val('journée');
            rendreDDLEditable();
        }
    })
});


// **************************************************************

var preventDefaultBehavior = function(e) {
    e.preventDefault();
}

// Simule le readonly sur une liste déroulante en ajoutant une classe qui cache la flèche et le contour
// et en enlevant la fonctionnalité de clic dessus

function rendreDDLReadOnly(e) {

    // on ajoute une classe qui enlèvera l'encadré et la flèche de droite
    $('#louvre_ticketingbundle_booking_duration').addClass('readonly');

    // on empêche la liste de s'ouvrir sur le clic (en fait, l'ouverture est programmée sur le mousedown)
    $('#louvre_ticketingbundle_booking_duration').bind('mousedown', preventDefaultBehavior);


}

// Enlève la simulation readonly sur une liste déroulante

function rendreDDLEditable(e) {

    // on enlève la classe qui enlèvait l'encadré et la flèche de droite
    $('#louvre_ticketingbundle_booking_duration').removeClass('readonly');

    // on remet le comportement par défaut sur le clic
    $('#louvre_ticketingbundle_booking_duration').unbind('mousedown', preventDefaultBehavior);

}
