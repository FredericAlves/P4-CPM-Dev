    $(document).ready(function() {
        // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
        var $container = $('div#louvre_ticketingbundle_bookingsteptwo_tickets');

        var numberOfTickets = document.getElementById("numberOfTickets");



        for (var compteur = 1; compteur <= numberOfTickets.innerHTML; compteur++){
            addTicket($container);
        }

        function addTicket($container) {
            // Dans le contenu de l'attribut « data-prototype », on remplace :
            // - le texte "__name__label__" qu'il contient par le label du champ
            // - le texte "__name__" qu'il contient par le numéro du champ
            var template = $container.attr('data-prototype')
                .replace(/__name__label__/g, 'Visiteur n° ' + (compteur))
                .replace(/louvre_ticketingbundle_booking_tickets___name__/g, 'louvre_ticketingbundle_booking_tickets___name__' + (compteur))
            ;

            // On crée un objet jquery qui contient ce template
            var $prototype = $(template);



            // On ajoute le prototype modifié à la fin de la balise <div>
            $container.append($prototype);


        }

















        //
        // // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
        // var index = $container.find(':input').length;
        //
        // // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
        // $('#add_ticket').click(function(e) {
        //     addTicket($container);
        //
        //     e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        //     return false;
        // });
        //
        // // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
        // if (index == 0) {
        //     addTicket($container);
        // } else {
        //     // S'il existe déjà des catégories, on ajoute un lien de suppression pour chacune d'entre elles
        //     $container.children('div').each(function() {
        //         addDeleteLink($(this));
        //     });
        // }
        //
        // // La fonction qui ajoute un formulaire TicketType
        // function addTicket($container) {
        //     // Dans le contenu de l'attribut « data-prototype », on remplace :
        //     // - le texte "__name__label__" qu'il contient par le label du champ
        //     // - le texte "__name__" qu'il contient par le numéro du champ
        //     var template = $container.attr('data-prototype')
        //        .replace(/__name__label__/g, 'Visiteur n° ' + (index+1))
        //        .replace(/louvre_ticketingbundle_booking_tickets___name__/g, 'louvre_ticketingbundle_booking_tickets___name__' + (index+1))
        //     ;
        //
        //     // On crée un objet jquery qui contient ce template
        //     var $prototype = $(template);
        //
        //     // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
        //     if (index > 0) {
        //         addDeleteLink($prototype);
        //     }
        //
        //     // si il y a plusieurs visiteur on supprime le delete sauf pour le dernier
        //     if (index > 1) {
        //         delDeleteLink();
        //     }
        //
        //     // On ajoute le prototype modifié à la fin de la balise <div>
        //     $container.append($prototype);
        //
        //     // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        //     index++;
        // }
        //
        // // La fonction qui ajoute un lien de suppression d'une catégorie
        // function addDeleteLink($prototype) {
        //     // Création du lien
        //     var $deleteLink = $('<a href="#" class="btn btn-danger visitor' + (index+1) + ' ">Supprimer</a>');
        //
        //
        //     // Ajout du lien
        //     $prototype.append($deleteLink);
        //
        //     // Ajout du listener sur le clic du lien pour effectivement supprimer la catégorie
        //     $deleteLink.click(function(e) {
        //         $prototype.remove();
        //         if (index > 1) {
        //             addDeleteLink2();
        //         }
        //         index--;
        //         e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        //
        //         return false;
        //     });
        // }
        //
        // function addDeleteLink2() {
        //     // Création du lien
        //     var $deleteLink2 = $('<a href="#" class="btn btn-danger visitor' + (index) + ' ">Supprimer</a>');
        //     $('#louvre_ticketingbundle_booking_tickets___name__'+ (index-1)).after($deleteLink2);
        //
        //
        // }
        //
        // function delDeleteLink() {
        //     // Création du lien
        //
        //     $(".visitor" + index).remove();
        //
        //
        //
        // }
    });
